<?php

namespace App\Controller;

use App\Entity\PullRequest;
use App\UseCase\CalculateQuoteCommand;
use App\UseCase\CalculateQuoteUseCase;
use App\UseCase\CreatePullRequestCommand;
use App\UseCase\CreatePullRequestUseCase;
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
     * @Route(methods={"POST"})
     */
    public function new(Request $request): JsonResponse
    {
        $writer = $request->get('writer');
        $code = $request->get('code');
        $assignedReviewers = $request->get('assignedReviewers');
        $revisionDueDate = $request->get('revisionDueDate');

        try {
            $pullRequest = (new CreatePullRequestUseCase())->execute(new CreatePullRequestCommand($code, $writer, $revisionDueDate, $assignedReviewers));
            $quote = (new CalculateQuoteUseCase())->execute(new CalculateQuoteCommand($code, $revisionDueDate, $assignedReviewers));
            $pullRequest->setQuote($quote);
        } catch (\DomainException $exception){
            return new JsonResponse(['error' => 'Code is required'], Response::HTTP_CONFLICT);
        }

        // Persist
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($pullRequest);
        $entityManager->flush();

        return new JsonResponse(array("id" => $pullRequest->getId()), Response::HTTP_CREATED);
    }


    /**
     * @Route("/{id}", name="pull_request_edit", methods={"PUT"})
     */
    public function edit(Request $request, PullRequest $pullRequest): JsonResponse
    {
        $code = $request->get('code');
        if (empty($code)) {
            return new JsonResponse(array('error' => 'Code is required'), Response::HTTP_CONFLICT);
        }

        $pullRequest->setCode($code);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($pullRequest);
        $entityManager->flush();

        return new JsonResponse(array("id" => $pullRequest->getId()));
    }
}
