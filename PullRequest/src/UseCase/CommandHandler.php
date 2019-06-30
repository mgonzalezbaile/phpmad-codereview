<?php

declare(strict_types=1);

namespace App\UseCase;

interface CommandHandler
{
    public function handle(Command $command): DomainEventList;
}
