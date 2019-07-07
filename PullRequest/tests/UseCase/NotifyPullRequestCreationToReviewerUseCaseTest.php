<?php

declare(strict_types=1);

namespace App\Tests\UseCase;

use App\Tests\Double\MailerServiceSpy;
use App\UseCase\NotifyPullRequestCreationToReviewerCommand;
use App\UseCase\NotifyPullRequestCreationToReviewerUseCase;
use PHPUnit\Framework\TestCase;

class NotifyPullRequestCreationToReviewerUseCaseTest extends TestCase
{
    public function testShouldSendNotification()
    {
        $mailerSpy = new MailerServiceSpy();

        (new NotifyPullRequestCreationToReviewerUseCase($mailerSpy))->handle(new NotifyPullRequestCreationToReviewerCommand('some id', 'some reviewer'));

        $this->assertEquals(true, $mailerSpy->isSentMethodCalled());
    }
}
