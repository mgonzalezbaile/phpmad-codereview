<?php


namespace App\Tests\UseCase;


use App\Entity\PullRequest;
use App\Event\ApprovePullRequestFailed;
use App\Event\PullRequestApproved;
use App\Repository\PullRequestRepository;
use App\UseCase\ApprovePullRequestCommand;
use App\UseCase\ApprovePullRequestUseCase;

class ApprovePullRequestUseCaseTest extends UseCaseScenario
{
    public function setUp(): void
    {
        parent::setUp();

        $this
            ->setUpScenario()
            ->withUseCase(ApprovePullRequestUseCase::class)
            ->withRepository(PullRequestRepository::class);
    }

    /**
     * @test
     */
    public function testShouldApprovePullRequest()
    {
        $approver = 'some reviewer';
        $pullRequestId = '123';
        $aPullRequest = (new PullRequest())->withId($pullRequestId)->withApprovers([])->withAssignedReviewers([$approver, 'another reviewer']);

        $this
            ->given($aPullRequest)
            ->when(new ApprovePullRequestCommand($pullRequestId, $approver))
            ->then(new PullRequestApproved($pullRequestId, $approver))
            ->andProjections($aPullRequest->withApprovers([$approver]));
    }

    /**
     * @test
     */
    public function testShouldFailApprovingPullRequest_When_UnassignedApprover()
    {
        $approver = 'some unassigned approver';
        $pullRequestId = '123';
        $aPullRequest = (new PullRequest())->withId($pullRequestId)->withAssignedReviewers(['some reviewer', 'another reviewer']);

        $this
            ->given($aPullRequest)
            ->when(new ApprovePullRequestCommand($pullRequestId, $approver))
            ->then(ApprovePullRequestFailed::dueToUnassignedApprover($pullRequestId, $approver))
            ->andProjections($aPullRequest);
    }

    /**
     * @test
     */
    public function testShouldFailApprovingPullRequest_When_AlreadyApproved()
    {
        $approver = 'some reviewer';
        $pullRequestId = '123';
        $aPullRequest = (new PullRequest())
            ->withId($pullRequestId)
            ->withAssignedReviewers([$approver, 'another reviewer'])
            ->withIsAlreadyApproved(true);

        $this
            ->given($aPullRequest)
            ->when(new ApprovePullRequestCommand($pullRequestId, $approver))
            ->then(ApprovePullRequestFailed::dueToWasAlreadyApproved($pullRequestId))
            ->andProjections($aPullRequest);
    }
}
