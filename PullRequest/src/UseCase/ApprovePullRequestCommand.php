<?php

declare(strict_types=1);

namespace App\UseCase;

class ApprovePullRequestCommand implements Command
{
    /**
     * @var string
     */
    private $pullRequestId;

    /**
     * @var string
     */
    private $approver;

    public function __construct(string $pullRequestId, string $approver)
    {
        $this->pullRequestId = $pullRequestId;
        $this->approver      = $approver;
    }

    public function pullRequestId(): string
    {
        return $this->pullRequestId;
    }

    public function approver(): string
    {
        return $this->approver;
    }
}
