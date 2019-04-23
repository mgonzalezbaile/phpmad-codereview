<?php

declare(strict_types=1);

namespace App\UseCase;

class NotifyPullRequestCreationToReviewerCommand
{
    /**
     * @var string
     */
    private $reviewer;

    public function __construct(string $reviewer)
    {
        $this->reviewer = $reviewer;
    }

    public function reviewer(): string
    {
        return $this->reviewer;
    }
}
