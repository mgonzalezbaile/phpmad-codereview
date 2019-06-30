<?php

declare(strict_types=1);

namespace App\Middleware;

use App\UseCase\DomainEventList;
use App\UseCase\ProjectionList;

class CommandResponse
{
    /**
     * @var DomainEventList
     */
    private $domainEventList;

    /**
     * @var ProjectionList
     */
    private $projectionList;

    public function __construct(DomainEventList $domainEventList, ProjectionList $projectionList)
    {
        $this->domainEventList = $domainEventList;
        $this->projectionList  = $projectionList;
    }

    public function domainEventList(): DomainEventList
    {
        return $this->domainEventList;
    }

    public function projectionList(): ProjectionList
    {
        return $this->projectionList;
    }
}
