<?php

namespace App\Event;

use App\UseCase\DomainEvent;

class NotifyPullRequestCreationToReviewerSended implements DomainEvent
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $reviewer;

    public function __construct(string $id, string $reviewer)
    {
        $this->reviewer = $reviewer;
        $this->id = $id;
    }

    public function streamId(): string
    {
        return $this->id;
    }

    public function reviewer(): string
    {
        return $this->reviewer;
    }
}