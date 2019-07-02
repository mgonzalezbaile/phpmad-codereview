<?php

declare(strict_types=1);

namespace App\Event;

use App\UseCase\DomainEvent;

class QuoteCalculated implements DomainEvent
{
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
        int $quote
    ) {
        $this->id                 = $id;
        $this->quote              = $quote;
    }

    public function streamId(): string
    {
        return $this->id;
    }

    public function quote(): int
    {
        return $this->quote;
    }
}
