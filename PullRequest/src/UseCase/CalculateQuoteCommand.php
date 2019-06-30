<?php

declare(strict_types=1);

namespace App\UseCase;

class CalculateQuoteCommand implements Command
{
    /**
     * @var string
     */
    private $code;

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
        string $revisionDueDate,
        array $assignedReviewers
    ) {
        $this->code              = $code;
        $this->revisionDueDate   = $revisionDueDate;
        $this->assignedReviewers = $assignedReviewers;
    }

    public function code(): string
    {
        return $this->code;
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
