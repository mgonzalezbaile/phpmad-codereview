<?php

declare(strict_types=1);

namespace App\UseCase;

class NotifyPullRequestCreationToReviewerCommand implements Command
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
        $this->id       = $id;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function reviewer(): string
    {
        return $this->reviewer;
    }
}
