<?php

declare(strict_types=1);

namespace App\UseCase;

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
     */
    public function handle(Command $command): DomainEventList
    {
        $pullRequest = (new CreatePullRequestUseCase())->handle(new CreatePullRequestCommand($command->code(), $command->writer(), $command->revisionDueDate(), $command->assignedReviewers()));
        $quote       = (new CalculateQuoteUseCase())->handle(new CalculateQuoteCommand($command->code(), $command->revisionDueDate(), $command->assignedReviewers()));
        $pullRequest->setQuote($quote);
        foreach ($command->assignedReviewers() as $assignedReviewer) {
            (new NotifyPullRequestCreationToReviewerUseCase($this->mailerService))->handle(new NotifyPullRequestCreationToReviewerCommand($assignedReviewer));
        }

        return $pullRequest;
    }
}
