<?php

namespace App\Service;


class KataMailerService
{
    public function send(string $content, string $recipients): void
    {
        sleep(rand(3, 10));
    }
}
