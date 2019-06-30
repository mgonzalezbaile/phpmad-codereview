<?php

declare(strict_types=1);

namespace App\UseCase;

interface DomainEvent
{
    public function streamId(): string;
}
