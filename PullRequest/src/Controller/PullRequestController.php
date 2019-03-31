<?php

namespace App\Controller;

use App\Entity\PullRequest;
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
        $revisionDueDate = \DateTime::createFromFormat('Y-m-d', $request->get('revisionDueDate'));

        if (empty($code)) {
            return new JsonResponse(array('error' => 'Code is required'), Response::HTTP_CONFLICT);
        }

        $pullRequest = new PullRequest();
        $pullRequest->setCode($code);
        $pullRequest->setWriter($writer);
        $pullRequest->setCreatedAt(new \DateTime());
        $pullRequest->setRevisionDueDate($revisionDueDate);
        $pullRequest->setIsMerged(false);
        $pullRequest->setAssignedReviewers($assignedReviewers);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($pullRequest);
        $entityManager->flush();

        return new JsonResponse(array("id" => $pullRequest->getId()), Response::HTTP_CREATED);
    }


    /**
     * @Route("/{id}/edit", name="pull_request_edit", methods={"POST"})
     */
    public function edit(Request $request, PullRequest $pullRequest): JsonResponse
    {
        $code = $request->get('code');
        $pullRequest->setCode($code);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($pullRequest);
        $entityManager->flush();

        return new JsonResponse(array("id" => $pullRequest->getId()));
    }
}
