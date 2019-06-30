<?php

declare(strict_types=1);

namespace App\UseCase;

abstract class ConventionBasedProjector implements Projector
{
    public function project(DomainEvent $domainEvent, Projection $projection): Projection
    {
        $callMethod = $this->callMethod($domainEvent);

        return $this->$callMethod($domainEvent, $projection);
    }

    private function callMethod(DomainEvent $domainEvent)
    {
        $fqcnParts = explode('\\', get_class($domainEvent));

        return 'project' . end($fqcnParts);
    }
}
