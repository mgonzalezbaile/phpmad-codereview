<?php

declare(strict_types=1);

namespace App\Service;

interface MailerService
{
    public function send(string $content, string $recipients): void;
}
