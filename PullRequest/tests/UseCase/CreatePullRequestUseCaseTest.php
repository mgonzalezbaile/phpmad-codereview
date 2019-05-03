<?php


namespace App\Tests\UseCase;


use App\UseCase\CreatePullRequestCommand;
use App\UseCase\CreatePullRequestUseCase;
use PHPUnit\Framework\TestCase;

class CreatePullRequestUseCaseTest extends TestCase
{
    public function testShouldCreatePullRequest()
    {
        $code              = 'some code';
        $writer            = 'some writer';
        $revisionDueDate   = '2019-01-01';
        $assignedReviewers = [1, 2, 3];
        $pullRequest       = (new CreatePullRequestUseCase())->execute(new CreatePullRequestCommand(
            $code,
            $writer,
            $revisionDueDate,
            $assignedReviewers
        ));

        $this->assertEquals($code, $pullRequest->getCode());
        $this->assertEquals($writer, $pullRequest->getWriter());
        $this->assertEquals($assignedReviewers, $pullRequest->getAssignedReviewers());
    }

    public function testShouldFailWhenCodeEmpty()
    {
        $this->expectException(\DomainException::class);

        $code              = '';
        $writer            = 'some writer';
        $revisionDueDate   = '2019-01-01';
        $assignedReviewers = [1, 2, 3];

        (new CreatePullRequestUseCase())->execute(new CreatePullRequestCommand(
            $code,
            $writer,
            $revisionDueDate,
            $assignedReviewers
        ));
    }
}
