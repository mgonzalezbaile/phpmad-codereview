<?php

declare(strict_types=1);

namespace App\UseCase;

use ArrayIterator;
use IteratorAggregate;
use Webmozart\Assert\Assert;

class AggregateRootList implements IteratorAggregate
{
    /**
     * @var array
     */
    private $aggregateRoots;

    private function __construct(array $aggregateRoots)
    {
        $this->aggregateRoots = $aggregateRoots;
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public static function fromAggregateRoots(AggregateRoot ...$projections): self
    {
        return new self($projections);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->aggregateRoots);
    }

    public function asArray(): array
    {
        return $this->aggregateRoots;
    }

    public function appendAggregateRootList(self $projectionList): self
    {
        return new self(array_merge($this->aggregateRoots, $projectionList->asArray()));
    }

    public function addAggregateRoot(?AggregateRoot $aggregateRoot): self
    {
        return new self(array_filter(array_merge($this->aggregateRoots, [$aggregateRoot])));
    }
}
