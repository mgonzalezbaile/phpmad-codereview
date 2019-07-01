<?php

declare(strict_types=1);

namespace App\Middleware;

use App\UseCase\DomainEventList;
use App\UseCase\AggregateRootList;

class CommandResponse
{
    /**
     * @var DomainEventList
     */
    private $domainEventList;

    /**
     * @var AggregateRootList
     */
    private $projectionList;

    public function __construct(DomainEventList $domainEventList, AggregateRootList $projectionList)
    {
        $this->domainEventList = $domainEventList;
        $this->projectionList  = $projectionList;
    }

    public function domainEventList(): DomainEventList
    {
        return $this->domainEventList;
    }

    public function projectionList(): AggregateRootList
    {
        return $this->projectionList;
    }
}
