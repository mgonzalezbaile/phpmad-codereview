<?php


namespace App\Middleware;


use App\UseCase\Command;
use App\UseCase\IExecuteCommand;
use App\UseCase\PersistEntityUseCaseDecorator;
use App\UseCase\TraceUseCaseDecorator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class CommonCommandBus implements IExecuteCommand
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var IExecuteCommand
     */
    private $useCase;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function withUseCase(string $useCase): self
    {
        $this->useCase = $useCase;

        return $this;
    }

    public function execute(Command $command)
    {
        $this->useCase = new TraceUseCaseDecorator(
            new PersistEntityUseCaseDecorator(
                $this->container->get($this->useCase),
                $this->entityManager
            ),
            $this->logger
        );

        return $this->useCase->execute($command);
    }
}
