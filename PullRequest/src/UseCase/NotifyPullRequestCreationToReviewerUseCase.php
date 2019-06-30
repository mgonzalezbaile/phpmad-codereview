<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Service\MailerService;

class NotifyPullRequestCreationToReviewerUseCase implements CommandHandler
{
    /**
     * @var MailerService
     */
    private $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    /**
     * @param NotifyPullRequestCreationToReviewerCommand $command
     */
    public function handle(Command $command): DomainEventList
    {
        $this->mailerService->send('Hello reviewer, You have a pull request to review.', $command->reviewer());

        return DomainEventList::fromDomainEvents();
    }
}
