<?php

declare(strict_types=1);

namespace App\Tests\UseCase;

use App\Entity\PullRequestProjection;
use App\Repository\PullRequestProjectionPersistence;
use App\UseCase\CreatePullRequestCommand;
use App\Event\PullRequestCreated;
use App\UseCase\CreatePullRequestUseCase;

class CreatePullRequestUseCaseTest extends UseCaseScenario
{
    public function testShouldCreatePullRequest()
    {
        $this
            ->setUpScenario()
            ->withUseCase(CreatePullRequestUseCase::class)
            ->withProjectionPersistence(PullRequestProjectionPersistence::class);

        $code               = 'some code';
        $writer             = 'some writer';
        $revisionDueDateStr = '2019-01-01';
        $assignedReviewers  = ['some reviewer'];
        $revisionDueDate    = \DateTimeImmutable::createFromFormat('Y-m-d', $revisionDueDateStr);
        $expectedId         = 'some id';

        $this
            ->when(
                new CreatePullRequestCommand($expectedId, $code, $writer, $revisionDueDateStr, $assignedReviewers)
            )
            ->then(
                new PullRequestCreated(
                    $expectedId,
                    $code,
                    $writer,
                    $revisionDueDate,
                    $assignedReviewers
                )
            )
            ->andProjections(
                (new PullRequestProjection())
                    ->withCode($code)
                    ->withWriter($writer)
                    ->withRevisionDueDate($revisionDueDate)
                    ->withId($expectedId)
                    ->withIsMerged(false)
                    ->withAssignedReviewers($assignedReviewers)
            );
    }
}
