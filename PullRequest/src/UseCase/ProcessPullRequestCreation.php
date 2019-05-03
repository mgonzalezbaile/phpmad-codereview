<?php


namespace App\UseCase;


use App\Entity\PullRequest;
use App\Service\MailerService;

class ProcessPullRequestCreation
{
    private $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    public function execute(ProcessPullRequestCreationCommand $command): PullRequest
    {
        $pullRequest = (new CreatePullRequestUseCase())->execute(new CreatePullRequestCommand($command->code(), $command->writer(), $command->revisionDueDate(), $command->assignedReviewers()));
        $quote       = (new CalculateQuoteUseCase())->execute(new CalculateQuoteCommand($command->code(), $command->revisionDueDate(), $command->assignedReviewers()));
        $pullRequest->setQuote($quote);
        foreach ($command->assignedReviewers() as $assignedReviewer) {
            (new NotifyPullRequestCreationToReviewerUseCase($this->mailerService))->execute(new NotifyPullRequestCreationToReviewerCommand($assignedReviewer));
        }

        return $pullRequest;
    }
}
