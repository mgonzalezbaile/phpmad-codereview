<?php

declare(strict_types=1);

namespace App\Middleware;

use App\UseCase\Command;
use App\UseCase\CommandHandler;
use App\UseCase\DomainEvent;
use App\UseCase\AggregateRootList;
use App\UseCase\DomainEventList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class CommonCommandHandlerBus
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var CommandHandler
     */
    private $useCase;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ServiceEntityRepository
     */
    private $repository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        ContainerInterface $container,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->container     = $container;
        $this->entityManager = $entityManager;
        $this->logger        = $logger;
    }

    public function withUseCase(string $useCase): self
    {
        $this->useCase = $this->container->get($useCase);

        return $this;
    }

    public function withRepository(string $repository): self
    {
        $this->repository = $this->container->get($repository);

        return $this;
    }

    public function handle(Command $command): CommandResponse
    {
        try {
            $this->entityManager->beginTransaction();

            $domainEvents = $this->useCase->handle($command);

            $aggregateRootList = $this->projectEvents($domainEvents);

            $this->persist($aggregateRootList);

            $this->entityManager->commit();

            return new CommandResponse($domainEvents, $aggregateRootList);
        } catch (\Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }
    }

    private function projectMethodOf(DomainEvent $domainEvent): string
    {
        $fqcnParts = explode('\\', get_class($domainEvent));

        return 'project' . end($fqcnParts);
    }

    private function projectEvents(DomainEventList $domainEvents): AggregateRootList
    {
        $aggregateRootList = AggregateRootList::empty();
        foreach ($domainEvents as $domainEvent) {
            $projectMethod = $this->projectMethodOf($domainEvent);

            if (method_exists($this->useCase, $projectMethod) && $this->repository) {
                $newAggregateRoots = $this->useCase->$projectMethod($domainEvent, $this->repository->find($domainEvent->streamId()));
                $aggregateRootList = $aggregateRootList->appendAggregateRootList($newAggregateRoots);
            }
        }

        return $aggregateRootList;
    }

    private function persist(AggregateRootList $aggregateRootList): void
    {
        foreach ($aggregateRootList as $aggregateRoot) {
            $this->entityManager->merge($aggregateRoot);
            $this->entityManager->flush();
        }
    }
}
