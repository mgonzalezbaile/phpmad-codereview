<?php

declare(strict_types=1);

namespace App\UseCase;

class PullRequest
{
    protected $id;

    /**
     * @var array
     */
    protected $assignedReviewers;

    public static function approverCanApprove(self $pullRequest, string $approver): bool
    {
        return in_array($approver, $pullRequest->assignedReviewers);
    }
}
