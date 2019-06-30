<?php

declare(strict_types=1);

namespace App\UseCase;

interface Projector
{
    public function project(DomainEvent $domainEventList, Projection $projection): Projection;

    public function findOfId(string $projectionId): Projection;
}
