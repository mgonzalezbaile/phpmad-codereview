<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Entity\PullRequest;
use App\Event\ApprovePullRequestFailed;
use App\Event\PullRequestApproved;
use App\Repository\PullRequestRepository;

class ApprovePullRequestUseCase implements CommandHandler
{
    /**
     * @var PullRequestRepository
     */
    private $repository;

    public function __construct(PullRequestRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ApprovePullRequestCommand $command
     * @return DomainEventList
     */
    public function handle(Command $command): DomainEventList
    {
        $pullRequest = $this->repository->ofId($command->pullRequestId());

        if (!in_array($command->approver(), $pullRequest->assignedReviewers())) {
            return DomainEventList::fromDomainEvents(ApprovePullRequestFailed::dueTo($command->pullRequestId(), ApprovePullRequestFailed::APPROVER_CANNOT_APPROVE_REASON));
        }

        return DomainEventList::fromDomainEvents(new PullRequestApproved($command->pullRequestId(), $command->approver()));
    }

    public function projectPullRequestApproved(PullRequestApproved $event, PullRequest $projection): AggregateRootList
    {
        $projection = $projection->withApprovers(array_merge($projection->approvers(), [$event->approver()]));

        return AggregateRootList::fromAggregateRoots($projection);
    }
}
