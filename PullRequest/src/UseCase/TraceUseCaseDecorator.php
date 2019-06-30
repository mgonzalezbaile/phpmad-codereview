<?php

declare(strict_types=1);

namespace App\UseCase;

use Psr\Log\LoggerInterface;

class TraceUseCaseDecorator implements CommandHandler
{
    /**
     * @var CommandHandler
     */
    private $useCase;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(CommandHandler $useCase, LoggerInterface $logger)
    {
        $this->useCase = $useCase;
        $this->logger  = $logger;
    }

    public function handle(Command $command)
    {
        $this->logger->info('START USE CASE');
        $result = $this->useCase->handle($command);
        $this->logger->info('END USE CASE');

        return $result;
    }
}
