<?php

namespace App\Controller;

use App\Entity\PullRequest;
use App\UseCase\ProcessPullRequestCreation;
use App\UseCase\ProcessPullRequestCreationCommand;
use Doctrine\Common\Persistence\ObjectManager;
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
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ProcessPullRequestCreation
     */
    private $processPullRequestCreation;

    public function __construct(ObjectManager $objectManager, ProcessPullRequestCreation $processPullRequestCreation)
    {
        $this->objectManager            = $objectManager;
        $this->processPullRequestCreation = $processPullRequestCreation;
    }

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
            $pullRequest = $this->processPullRequestCreation->execute(new ProcessPullRequestCreationCommand($writer, $code, $assignedReviewers, $revisionDueDate));
        } catch (\DomainException $exception){
            return new JsonResponse(['error' => 'Code is required'], Response::HTTP_CONFLICT);
        }

        // Persist
        $this->objectManager->persist($pullRequest);
        $this->objectManager->flush();

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
