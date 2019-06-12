<?php


namespace App\UseCase;


class ProcessPullRequestCreationCommand implements Command
{
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

    public function __construct(string $writer, string $code, array $assignedReviewers, string $revisionDueDate)
    {
        $this->code              = $code;
        $this->assignedReviewers = $assignedReviewers;
        $this->revisionDueDate   = $revisionDueDate;
        $this->writer            = $writer;
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