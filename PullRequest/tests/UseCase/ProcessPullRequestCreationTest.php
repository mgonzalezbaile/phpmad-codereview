<?php


namespace App\Tests\UseCase;


use App\Tests\Double\MailerServiceSpy;
use App\UseCase\ProcessPullRequestCreation;
use App\UseCase\ProcessPullRequestCreationCommand;
use PHPUnit\Framework\TestCase;

class ProcessPullRequestCreationTest extends TestCase
{
    public function testShouldManagePullRequestCreationProcess()
    {
        $mailerSpy = new MailerServiceSpy();

        $writer            = 'writer';
        $code              = 'code';
        $assignedReviewers = ['reviewer1'];
        $revisionDueDate   = '2019-01-01';
        $pullRequest       = (new ProcessPullRequestCreation($mailerSpy))->execute(new ProcessPullRequestCreationCommand($writer, $code, $assignedReviewers, $revisionDueDate));

        $this->assertEquals($writer, $pullRequest->getWriter());
        $this->assertEquals($code, $pullRequest->getCode());
        $this->assertEquals($assignedReviewers, $pullRequest->getAssignedReviewers());
        $this->assertEquals(11000, $pullRequest->getQuote());
        $this->assertEquals(count($assignedReviewers), $mailerSpy->sentMethodTimesCalled());
    }
}
