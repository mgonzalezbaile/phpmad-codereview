<?php

declare(strict_types=1);

namespace App\UseCase;

interface DomainEventFailure
{
    public function reason(): string;
}
