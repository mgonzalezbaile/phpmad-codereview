<?php

declare(strict_types=1);

namespace App\UseCase;

use ArrayIterator;
use IteratorAggregate;

class AggregateRootList implements IteratorAggregate
{
    /**
     * @var AggregateRoot
     */
    private $projections;

    private function __construct(array $projections)
    {
        $this->projections = $projections;
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
        return new ArrayIterator($this->projections);
    }

    public function asArray(): array
    {
        return $this->projections;
    }

    public function appendAggregateRootList(self $projectionList): self
    {
        return new self(array_merge($this->projections, $projectionList->asArray()));
    }
}
