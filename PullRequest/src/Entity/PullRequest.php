<?php

namespace App\Entity;

use App\Service\KataMailerService;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PullRequestRepository")
 * @ORM\Table(name="pull_request")
 */
class PullRequest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $code;

    /**
     * @ORM\Column(type="string")
     */
    private $writer;

    /**
     * @ORM\Column(type="array")
     */
    private $assignedReviewers;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isMerged;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $revisionDueDate;

    /**
     * @ORM\Column(type="float")
     */
    private $quote;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getWriter(): ?string
    {
        return $this->writer;
    }

    public function setWriter(string $writer): self
    {
        $this->writer = $writer;

        return $this;
    }

    public function getAssignedReviewers(): ?array
    {
        return $this->assignedReviewers;
    }

    public function setAssignedReviewers(array $assignedReviewers): self
    {
        $this->assignedReviewers = $assignedReviewers;

        return $this;
    }

    public function getIsMerged(): ?bool
    {
        return $this->isMerged;
    }

    public function setIsMerged(bool $isMerged): self
    {
        $this->isMerged = $isMerged;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getQuote()
    {
        return $this->quote;
    }

    public function setQuote(float $quote): void
    {
        $this->quote = $quote;
    }

    public function getRevisionDueDate(): \DateTime
    {
        return $this->revisionDueDate;
    }

    public function setRevisionDueDate(\DateTimeInterface $revisionDueDate): void
    {
        $this->revisionDueDate = $revisionDueDate;
    }
}
