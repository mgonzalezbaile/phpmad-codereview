<?php

declare(strict_types=1);

namespace App\Event;

use App\UseCase\DomainEventFailure;

class ApprovePullRequestFailed implements DomainEventFailure
{
    /**
     * @var string
     */
    private $pullRequestId;

    /**
     * @var string
     */
    private $reason;

    public static function dueToUnassignedApprover(string $pullRequestId, string $approver): self
    {
        return new self($pullRequestId, "Approver '{$approver}' is not a reviewer of PR {$pullRequestId}");
    }

    public static function dueToWasAlreadyApproved(string $pullRequestId): self
    {
        return new self($pullRequestId, "PR {$pullRequestId} was already approved");
    }

    public function __construct(string $pullRequestId, string $reason)
    {
        $this->pullRequestId = $pullRequestId;
        $this->reason        = $reason;
    }

    public function streamId(): string
    {
        return $this->pullRequestId;
    }

    public function reason(): string
    {
        return $this->reason;
    }
}
