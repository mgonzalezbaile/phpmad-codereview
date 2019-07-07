<?php

declare(strict_types=1);

namespace App\Controller;

use App\Middleware\CommonCommandBus;
use App\Repository\PullRequestRepository;
use App\UseCase\ApprovePullRequestCommand;
use App\UseCase\ApprovePullRequestUseCase;
use App\UseCase\DomainEventFailure;
use App\UseCase\ProcessPullRequestCreation;
use App\UseCase\ProcessPullRequestCreationCommand;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pull-requests")
 */
class PullRequestController extends AbstractController
{
    /**
     * @var CommonCommandBus
     */
    private $commandBus;

    public function __construct(CommonCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Route(methods={"POST"})
     */
    public function new(Request $request): JsonResponse
    {
        $writer            = $request->get('writer');
        $code              = $request->get('code');
        $assignedReviewers = $request->get('assignedReviewers');
        $revisionDueDate   = $request->get('revisionDueDate');
        $id                = Uuid::uuid4()->toString();

        $commandResponse = $this->commandBus
            ->withUseCase(ProcessPullRequestCreation::class)
            ->withRepository(PullRequestRepository::class)
            ->handle(new ProcessPullRequestCreationCommand($id, $writer, $code, $assignedReviewers, $revisionDueDate));

        foreach ($commandResponse->domainEventList()->asArray() as $domainEvent) {
            if ($domainEvent instanceof DomainEventFailure) {
                return new JsonResponse(['error' => $domainEvent->reason()], Response::HTTP_CONFLICT);
            }
        }

        return new JsonResponse(['id' => $id], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{pullRequestId}/approve", name="pull_request_approve", methods={"PUT"})
     */
    public function approve(Request $request, string $pullRequestId): JsonResponse
    {
        $reviewer = $request->get('reviewer');
        if (empty($reviewer)) {
            return new JsonResponse(['error' => 'Reviewer is required'], Response::HTTP_CONFLICT);
        }

        $commandResponse = $this->commandBus
            ->withUseCase(ApprovePullRequestUseCase::class)
            ->withRepository(PullRequestRepository::class)
            ->handle(new ApprovePullRequestCommand($pullRequestId, $reviewer));

        foreach ($commandResponse->domainEventList()->asArray() as $domainEvent) {
            if ($domainEvent instanceof DomainEventFailure) {
                return new JsonResponse(['error' => $domainEvent->reason()], Response::HTTP_CONFLICT);
            }
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
