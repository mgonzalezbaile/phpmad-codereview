<?php

declare(strict_types=1);

namespace App\Event;

use App\UseCase\DomainEvent;
use App\UseCase\DomainEventFailure;

class CreatePullRequestFailed implements DomainEvent, DomainEventFailure
{
    /**
     * @var string
     */
    private $reason;

    public static function dueTo(string $reason): self
    {
        return new self("Create Pull Request failed due to {$reason}");
    }

    private function __construct(string $reason)
    {
        $this->reason = $reason;
    }

    public function streamId(): string
    {
        return '';
    }

    public function reason(): string
    {
        return $this->reason;
    }
}
