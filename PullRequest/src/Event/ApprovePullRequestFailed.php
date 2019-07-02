<?php

declare(strict_types=1);

namespace App\Event;

use App\UseCase\DomainEvent;
use App\UseCase\DomainEventFailure;

class ApprovePullRequestFailed implements DomainEvent, DomainEventFailure
{
    const APPROVER_CANNOT_APPROVE_REASON = 'approver cannot approve';

    /**
     * @var string
     */
    private $pullRequestId;

    /**
     * @var string
     */
    private $failureReason;

    public static function dueTo(string $pullRequestId, string $failureReason): self
    {
        return new self($pullRequestId, $failureReason);
    }

    private function __construct(string $pullRequestId, string $failureReason)
    {
        $this->pullRequestId = $pullRequestId;
        $this->failureReason = $failureReason;
    }

    public function streamId(): string
    {
        return $this->pullRequestId;
    }

    public function reason(): string
    {
        return $this->failureReason;
    }
}
