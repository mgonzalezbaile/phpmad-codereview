<?php

namespace App\UseCase;


interface DomainEventFailure
{
    public function reason(): string;
}