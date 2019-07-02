<?php

declare(strict_types=1);

namespace App\Event;

use App\UseCase\DomainEvent;
use DateTimeImmutable;

class PullRequestCreated implements DomainEvent
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $writer;

    /**
     * @var DateTimeImmutable
     */
    private $revisionDueDate;

    /**
     * @var array
     */
    private $assignedReviewers;

    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $quote;

    public function __construct(
        string $id,
        string $code,
        string $writer,
        int $quote,
        DateTimeImmutable $revisionDueDate,
        array $assignedReviewers
    ) {
        $this->code              = $code;
        $this->writer            = $writer;
        $this->quote = $quote;
        $this->revisionDueDate   = $revisionDueDate;
        $this->assignedReviewers = $assignedReviewers;
        $this->id                = $id;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function writer(): string
    {
        return $this->writer;
    }

    public function revisionDueDate(): DateTimeImmutable
    {
        return $this->revisionDueDate;
    }

    public function assignedReviewers(): array
    {
        return $this->assignedReviewers;
    }

    public function streamId(): string
    {
        return $this->id;
    }

    public function quote(): int
    {
        return $this->quote;
    }
}
