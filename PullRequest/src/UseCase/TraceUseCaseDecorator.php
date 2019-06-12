<?php


namespace App\UseCase;


use Psr\Log\LoggerInterface;

class TraceUseCaseDecorator implements IExecuteCommand
{
    /**
     * @var IExecuteCommand
     */
    private $useCase;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(IExecuteCommand $useCase, LoggerInterface $logger)
    {
        $this->useCase = $useCase;
        $this->logger = $logger;
    }

    public function execute(Command $command)
    {
        $this->logger->info('START USE CASE');
        $result = $this->useCase->execute($command);
        $this->logger->info('END USE CASE');

        return $result;
    }
}