<?php

declare(strict_types=1);

namespace App\Tests\Double;

use App\Service\MailerService;

class MailerServiceSpy implements MailerService
{
    private $isSentMethodCalled = false;

    private $sentMethodTimesCalled = 0;

    public function send(string $content, string $recipients): void
    {
        $this->isSentMethodCalled = true;
        ++$this->sentMethodTimesCalled;
    }

    public function isSentMethodCalled(): bool
    {
        return $this->isSentMethodCalled;
    }

    public function sentMethodTimesCalled(): int
    {
        return $this->sentMethodTimesCalled;
    }
}
