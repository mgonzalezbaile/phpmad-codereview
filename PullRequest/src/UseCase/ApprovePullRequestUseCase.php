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
     */
    public function handle(Command $command): DomainEventList
    {
        $pullRequest = $this->repository->ofId($command->pullRequestId());

        if (!in_array($command->approver(), $pullRequest->assignedReviewers())) {
            return DomainEventList::fromDomainEvents(ApprovePullRequestFailed::dueToUnassignedApprover($command->pullRequestId(), $command->approver()));
        }

        if ($pullRequest->wasAlreadyApproved()) {
            return DomainEventList::fromDomainEvents(ApprovePullRequestFailed::dueToWasAlreadyApproved($command->pullRequestId()));
        }

        return DomainEventList::fromDomainEvents(new PullRequestApproved($command->pullRequestId(), $command->approver()));
    }

    public function projectPullRequestApproved(PullRequestApproved $event, PullRequest $pullRequest): AggregateRootList
    {
        return AggregateRootList::fromAggregateRoots($pullRequest->withApprovers(array_merge($pullRequest->approvers(), [$event->approver()])));
    }
}
