<?php

declare(strict_types=1);

namespace App\Middleware;

use App\UseCase\Command;
use App\UseCase\CommandHandler;
use App\UseCase\DomainEvent;
use App\UseCase\ProjectionList;
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
    private $projectionPersistence;

    /**
     * @var LoggerInterface
     */
    private $logger;

//    /**
//     * @var EventStore
//     */
//    private $eventStore;

    public function __construct(
        ContainerInterface $container,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
//        EventStore $eventStore
    ) {
        $this->container     = $container;
        $this->entityManager = $entityManager;
        $this->logger        = $logger;
//        $this->eventStore = $eventStore;
    }

    public function withUseCase(string $useCase): self
    {
        $this->useCase = $this->container->get($useCase);

        return $this;
    }

    public function withProjectionPersistence(string $projectionPersistence): self
    {
        $this->projectionPersistence = $this->container->get($projectionPersistence);

        return $this;
    }

    public function handle(Command $command): CommandResponse
    {
        try {
            $this->entityManager->beginTransaction();
            $domainEvents = $this->useCase->handle($command);
            $projections  = ProjectionList::empty();

            foreach ($domainEvents as $domainEvent) {
                $callMethod = $this->callMethod($domainEvent);

                $newProjections = $this->useCase->$callMethod($domainEvent, $this->projectionPersistence->find($domainEvent->streamId()));
                $projections    = $projections->appendProjectionList($newProjections);
            }

//            foreach ($domainEvents as $domainEvent) {
//                $this->eventStore->append($domainEvent);
//            }

            foreach ($projections as $projection) {
                $this->entityManager->merge($projection);
                $this->entityManager->flush();
            }

            $this->entityManager->commit();

            return new CommandResponse($domainEvents, $projections);
        } catch (\Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }
    }

    private function callMethod(DomainEvent $domainEvent)
    {
        $fqcnParts = explode('\\', get_class($domainEvent));

        return 'project' . end($fqcnParts);
    }
}
