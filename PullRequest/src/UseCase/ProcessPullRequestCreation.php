<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Entity\PullRequest;
use App\Event\PullRequestCreated;
use App\Service\MailerService;

class ProcessPullRequestCreation implements CommandHandler
{
    private $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    /**
     * @param ProcessPullRequestCreationCommand $command
     * @return DomainEventList
     */
    public function handle(Command $command): DomainEventList
    {
        $domainEventsListCalculateQuote    = (new CalculateQuoteUseCase())->handle(new CalculateQuoteCommand($command->id(), $command->code(), $command->revisionDueDate(), $command->assignedReviewers()));
        $quote = $domainEventsListCalculateQuote->getIterator()->current()->quote();
        $domainEventsListPullRequest = (new CreatePullRequestUseCase())->handle(new CreatePullRequestCommand($command->id(), $command->code(), $command->writer(), $quote, $command->revisionDueDate(), $command->assignedReviewers()));

        $domainEventsNotifyPullRequestCreation = [];
        foreach ($command->assignedReviewers() as $assignedReviewer) {
            $domainEventsNotifyPullRequestCreationAssignedReviewer = (new NotifyPullRequestCreationToReviewerUseCase($this->mailerService))->handle(new NotifyPullRequestCreationToReviewerCommand($command->id(),$assignedReviewer));
            $domainEventsNotifyPullRequestCreation = array_merge(
                $domainEventsNotifyPullRequestCreation,
                $domainEventsNotifyPullRequestCreationAssignedReviewer->asArray()
            );
        }

        return DomainEventList::fromDomainEventsArray(array_merge(
            $domainEventsListCalculateQuote->asArray(),
            $domainEventsListPullRequest->asArray(),
            $domainEventsNotifyPullRequestCreation
        ));
    }

    public function projectPullRequestCreated(PullRequestCreated $event, ?PullRequest $pullRequest): AggregateRootList
    {
        $pullRequest = (new PullRequest())
            ->withId($event->streamId())
            ->withCode($event->code())
            ->withWriter($event->writer())
            ->withQuote($event->quote())
            ->withRevisionDueDate($event->revisionDueDate())
            ->withIsMerged(false)
            ->withAssignedReviewers($event->assignedReviewers());

        return AggregateRootList::fromAggregateRoots($pullRequest);
    }
}
