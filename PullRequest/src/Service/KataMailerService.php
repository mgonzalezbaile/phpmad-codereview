<?php

declare(strict_types=1);

namespace App\Service;

class KataMailerService implements MailerService
{
    public function send(string $content, string $recipients): void
    {
        sleep(rand(3, 10));
    }
}
