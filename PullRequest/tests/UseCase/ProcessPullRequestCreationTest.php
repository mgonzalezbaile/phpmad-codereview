<?php

declare(strict_types=1);

namespace App\Tests\UseCase;

use App\Entity\PullRequest;
use App\Event\NotifyPullRequestCreationToReviewerSended;
use App\Event\PullRequestCreated;
use App\Event\QuoteCalculated;
use App\Repository\PullRequestRepository;
use App\UseCase\ProcessPullRequestCreation;
use App\UseCase\ProcessPullRequestCreationCommand;

class ProcessPullRequestCreationTest extends UseCaseScenario
{
    public function testShouldManagePullRequestCreationProcess()
    {
        $id                      = 'some id';
        $writer                  = 'writer';
        $code                    = 'code';
        $assignedReviewers       = ['reviewer1'];
        $revisionDueDate         = '2019-01-01';
        $revisionDueDateObject   = \DateTimeImmutable::createFromFormat('Y-m-d', $revisionDueDate);
        $quote                   = 11000;
        $aPullRequest            = (new PullRequest())->withId($id);

        $this
            ->setUpScenario()
            ->withUseCase(ProcessPullRequestCreation::class)
            ->withRepository(PullRequestRepository::class);

        $this
            ->given(
                $aPullRequest
            )
            ->when(
                new ProcessPullRequestCreationCommand($id, $writer, $code, $assignedReviewers, $revisionDueDate)
            )
            ->then(
                new QuoteCalculated($id, $quote),
                new PullRequestCreated($id, $code, $writer, $quote, $revisionDueDateObject, $assignedReviewers),
                new NotifyPullRequestCreationToReviewerSended($id, 'reviewer1')
            )
            ->andProjections(
                (new PullRequest())
                    ->withCode($code)
                    ->withWriter($writer)
                    ->withQuote($quote)
                    ->withRevisionDueDate($revisionDueDateObject)
                    ->withId($id)
                    ->withIsMerged(false)
                    ->withAssignedReviewers($assignedReviewers)
            );
    }
}
