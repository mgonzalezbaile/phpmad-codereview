<?php

declare(strict_types=1);

namespace App\UseCase;

class CreatePullRequestCommand implements Command
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
     * @var string
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
        string $revisionDueDate,
        array $assignedReviewers
    ) {
        $this->code              = $code;
        $this->writer            = $writer;
        $this->revisionDueDate   = $revisionDueDate;
        $this->assignedReviewers = $assignedReviewers;
        $this->id                = $id;
        $this->quote             = $quote;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function writer(): string
    {
        return $this->writer;
    }

    public function revisionDueDate(): string
    {
        return $this->revisionDueDate;
    }

    public function assignedReviewers(): array
    {
        return $this->assignedReviewers;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function quote(): int
    {
        return $this->quote;
    }
}
