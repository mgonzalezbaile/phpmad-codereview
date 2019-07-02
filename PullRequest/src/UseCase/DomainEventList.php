<?php

declare(strict_types=1);

namespace App\UseCase;

use ArrayIterator;
use IteratorAggregate;

class DomainEventList implements IteratorAggregate
{
    /**
     * @var DomainEvent
     */
    private $domainEvents;

    private function __construct(array $domainEvents)
    {
        $this->domainEvents = $domainEvents;
    }

    public static function fromDomainEvents(DomainEvent ...$domainEvent): self
    {
        return new self($domainEvent);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->domainEvents);
    }

    public function asArray(): array
    {
        return $this->domainEvents;
    }
}
