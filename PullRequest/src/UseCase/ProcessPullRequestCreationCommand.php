<?php

declare(strict_types=1);

namespace App\UseCase;

class ProcessPullRequestCreationCommand implements Command
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $assignedReviewers;

    /**
     * @var string
     */
    private $revisionDueDate;

    /**
     * @var string
     */
    private $writer;

    public function __construct(string $id, string $writer, string $code, array $assignedReviewers, string $revisionDueDate)
    {
        $this->id                = $id;
        $this->code              = $code;
        $this->assignedReviewers = $assignedReviewers;
        $this->revisionDueDate   = $revisionDueDate;
        $this->writer            = $writer;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function assignedReviewers(): array
    {
        return $this->assignedReviewers;
    }

    public function revisionDueDate(): string
    {
        return $this->revisionDueDate;
    }

    public function writer(): string
    {
        return $this->writer;
    }
}
