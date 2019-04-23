<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Service\MailerService;

class NotifyPullRequestCreationToReviewerUseCase
{
    /**
     * @var MailerService
     */
    private $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    public function execute(NotifyPullRequestCreationToReviewerCommand $command): void
    {
        $this->mailerService->send('Hello reviewer, You have a pull request to review.', $command->reviewer());
    }
}
