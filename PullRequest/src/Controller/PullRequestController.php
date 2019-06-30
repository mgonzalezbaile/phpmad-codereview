<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PullRequestProjection;
use App\Middleware\CommonCommandHandlerBus;
use App\Repository\PullRequestProjectionPersistence;
use App\UseCase\ProcessPullRequestCreation;
use App\UseCase\ProcessPullRequestCreationCommand;
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
     * @var CommonCommandHandlerBus
     */
    private $commandBus;

    public function __construct(CommonCommandHandlerBus $commandBus)
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

        try {
            /** @var PullRequestProjection $pullRequest */
            $pullRequest = $this->commandBus
                ->withUseCase(ProcessPullRequestCreation::class)
                ->withProjectionPersistence(PullRequestProjectionPersistence::class)
                ->handle(new ProcessPullRequestCreationCommand($writer, $code, $assignedReviewers, $revisionDueDate));
        } catch (\DomainException $exception) {
            return new JsonResponse(['error' => 'Code is required'], Response::HTTP_CONFLICT);
        }

        return new JsonResponse(['id' => $pullRequest->getId()], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="pull_request_edit", methods={"PUT"})
     */
    public function edit(Request $request, PullRequestProjection $pullRequest): JsonResponse
    {
        $code = $request->get('code');
        if (empty($code)) {
            return new JsonResponse(['error' => 'Code is required'], Response::HTTP_CONFLICT);
        }

        $pullRequest->setCode($code);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($pullRequest);
        $entityManager->flush();

        return new JsonResponse(['id' => $pullRequest->getId()]);
    }
}
