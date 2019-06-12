<?php


namespace App\UseCase;


use App\Entity\PullRequest;

class CreatePullRequestUseCase implements IExecuteCommand
{
    /**
     * @param CreatePullRequestCommand $command
     */
    public function execute(Command $command): PullRequest
    {
        // Validation
        if (empty($command->code())) {
            throw new \DomainException('Code cannot be empty');
        }

        // Create Pull Request
        $pullRequest = new PullRequest();
        $pullRequest->setCode($command->code());
        $pullRequest->setWriter($command->writer());
        $pullRequest->setCreatedAt(new \DateTime());
        $pullRequest->setRevisionDueDate(\DateTime::createFromFormat('Y-m-d', $command->revisionDueDate()));
        $pullRequest->setIsMerged(false);
        $pullRequest->setAssignedReviewers($command->assignedReviewers());

        return $pullRequest;
    }
}
