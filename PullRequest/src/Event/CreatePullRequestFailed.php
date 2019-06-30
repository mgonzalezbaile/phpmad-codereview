<?php

declare(strict_types=1);

namespace App\Event;

use App\UseCase\DomainEvent;

class CreatePullRequestFailed implements DomainEvent
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
}
