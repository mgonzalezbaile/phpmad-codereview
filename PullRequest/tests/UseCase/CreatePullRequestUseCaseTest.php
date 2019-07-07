<?php

declare(strict_types=1);

namespace App\Tests\UseCase;

use App\Entity\PullRequest;
use App\Repository\PullRequestRepository;
use App\UseCase\CreatePullRequestCommand;
use App\Event\PullRequestCreated;
use App\UseCase\CreatePullRequestUseCase;

class CreatePullRequestUseCaseTest extends UseCaseScenario
{
    public function setUp()
    {
        parent::setUp();

        $this
            ->setUpScenario()
            ->withUseCase(CreatePullRequestUseCase::class)
            ->withRepository(PullRequestRepository::class);
    }

    public function testShouldCreatePullRequest()
    {
        $code               = 'some code';
        $writer             = 'some writer';
        $revisionDueDateStr = '2019-01-01';
        $assignedReviewers  = ['some reviewer'];
        $revisionDueDate    = \DateTimeImmutable::createFromFormat('Y-m-d', $revisionDueDateStr);
        $expectedId         = 'some id';
        $quote              = 1000;

        $this
            ->when(
                new CreatePullRequestCommand($expectedId, $code, $writer, $quote, $revisionDueDateStr, $assignedReviewers)
            )
            ->then(
                new PullRequestCreated(
                    $expectedId,
                    $code,
                    $writer,
                    $quote,
                    $revisionDueDate,
                    $assignedReviewers
                )
            )
            ->andProjections(
                (new PullRequest())
                    ->withCode($code)
                    ->withWriter($writer)
                    ->withQuote($quote)
                    ->withRevisionDueDate($revisionDueDate)
                    ->withId($expectedId)
                    ->withIsMerged(false)
                    ->withAssignedReviewers($assignedReviewers)
            );
    }
}
