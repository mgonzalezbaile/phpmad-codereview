<?php

declare(strict_types=1);

namespace App\Entity;

use App\UseCase\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PullRequestRepository")
 * @ORM\Table(name="pull_request")
 */
class PullRequest implements AggregateRoot
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $writer;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $assignedReviewers = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $approvers = [];

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isMerged;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAlreadyApproved;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $revisionDueDate;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $quote;

    public function assignedReviewers()
    {
        return $this->assignedReviewers;
    }

    public function wasAlreadyApproved()
    {
        return $this->isAlreadyApproved;
    }

    public function approvers()
    {
        return $this->approvers;
    }

    public function withId($id): self
    {
        $clone     = clone $this;
        $clone->id = $id;

        return $clone;
    }

    public function withCode($code): self
    {
        $clone       = clone $this;
        $clone->code = $code;

        return $clone;
    }

    public function withWriter($writer): self
    {
        $clone         = clone $this;
        $clone->writer = $writer;

        return $clone;
    }

    public function withAssignedReviewers($assignedReviewers): self
    {
        $clone                    = clone $this;
        $clone->assignedReviewers = $assignedReviewers;

        return $clone;
    }

    public function withIsAlreadyApproved($isAlreadyApproved): self
    {
        $clone                    = clone $this;
        $clone->isAlreadyApproved = $isAlreadyApproved;

        return $clone;
    }

    public function withIsMerged($isMerged): self
    {
        $clone           = clone $this;
        $clone->isMerged = $isMerged;

        return $clone;
    }

    public function withCreatedAt($createdAt): self
    {
        $clone            = clone $this;
        $clone->createdAt = $createdAt;

        return $clone;
    }

    public function withUpdatedAt($updatedAt): self
    {
        $clone            = clone $this;
        $clone->updatedAt = $updatedAt;

        return $clone;
    }

    public function withRevisionDueDate($revisionDueDate): self
    {
        $clone                  = clone $this;
        $clone->revisionDueDate = $revisionDueDate;

        return $clone;
    }

    public function withQuote($quote): self
    {
        $clone        = clone $this;
        $clone->quote = $quote;

        return $clone;
    }

    public function withApprovers($approvers): self
    {
        $clone = clone $this;
        $clone->approvers = $approvers;
        return $clone;
    }
}
