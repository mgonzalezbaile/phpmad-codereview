<?php

declare(strict_types=1);

namespace App\Tests\UseCase;

use App\Entity\PullRequest;
use App\Event\ApprovePullRequestFailed;
use App\Repository\PullRequestRepository;
use App\UseCase\ApprovePullRequestCommand;
use App\UseCase\ApprovePullRequestUseCase;
use App\Event\PullRequestApproved;

class ApprovePullRequestUseCaseTest extends UseCaseScenario
{
    public function testShouldApprovePullRequest()
    {
        $this
            ->setUpScenario()
            ->withUseCase(ApprovePullRequestUseCase::class)
            ->withRepository(PullRequestRepository::class);

        $id                                = 'some id';
        $reviewer                          = 'some reviewer';
        $aPullRequestWithAssignedReviewers = (new PullRequest())->withId($id)->withAssignedReviewers([$reviewer]);
        $this
            ->given(
                $aPullRequestWithAssignedReviewers
            )
            ->when(
                new ApprovePullRequestCommand($id, $reviewer)
            )
            ->then(
                new PullRequestApproved($id, $reviewer)
            );
    }

    public function testShouldFailWhenApproverWasNotAssignedAsReviewer()
    {
        $this
            ->setUpScenario()
            ->withUseCase(ApprovePullRequestUseCase::class)
            ->withRepository(PullRequestRepository::class);

        $id                                = 'some id';
        $aReviewer                         = 'some reviewer';
        $aPullRequestWithAssignedReviewers = (new PullRequest())->withId($id)->withAssignedReviewers(['another reviewer']);
        $this
            ->given(
                $aPullRequestWithAssignedReviewers
            )
            ->when(
                new ApprovePullRequestCommand($id, $aReviewer)
            )
            ->then(
                ApprovePullRequestFailed::dueTo($id, ApprovePullRequestFailed::APPROVER_CANNOT_APPROVE_REASON)
            );
    }
}