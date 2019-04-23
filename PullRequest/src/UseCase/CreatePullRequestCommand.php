<?php


namespace App\UseCase;


class CreatePullRequestCommand
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

    public function __construct(
        string $code,
        string $writer,
        string $revisionDueDate,
        array $assignedReviewers
    ) {
        $this->code = $code;
        $this->writer = $writer;
        $this->revisionDueDate = $revisionDueDate;
        $this->assignedReviewers = $assignedReviewers;
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
}