<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Entity\PullRequest;
use App\Event\CreatePullRequestFailed;
use App\Event\PullRequestCreated;
use DateTimeImmutable;

class CreatePullRequestUseCase implements CommandHandler
{
    /**
     * @param CreatePullRequestCommand $command
     * @return DomainEventList
     */
    public function handle(Command $command): DomainEventList
    {
        if (empty($command->code())) {
            return DomainEventList::fromDomainEvents(CreatePullRequestFailed::dueTo('Wrong revision due date format given: ' . $command->revisionDueDate()));
        }

        $revisionDate = DateTimeImmutable::createFromFormat('Y-m-d', $command->revisionDueDate());
        if (!$revisionDate) {
            return DomainEventList::fromDomainEvents(CreatePullRequestFailed::dueTo('Wrong revision due date format given: ' . $command->revisionDueDate()));
        }

        return DomainEventList::fromDomainEvents(new PullRequestCreated(
            $command->id(),
            $command->code(),
            $command->writer(),
            $command->quote(),
            $revisionDate,
            $command->assignedReviewers()
        ));
    }

    public function projectPullRequestCreated(PullRequestCreated $event, ?PullRequest $projection): AggregateRootList
    {
        $projection = (new PullRequest())
            ->withId($event->streamId())
            ->withCode($event->code())
            ->withWriter($event->writer())
            ->withQuote($event->quote())
            ->withRevisionDueDate($event->revisionDueDate())
            ->withIsMerged(false)
            ->withAssignedReviewers($event->assignedReviewers());

        return AggregateRootList::fromAggregateRoots($projection);
    }
}
