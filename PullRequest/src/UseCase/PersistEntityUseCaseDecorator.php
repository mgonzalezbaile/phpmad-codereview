<?php

declare(strict_types=1);

namespace App\UseCase;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class PersistEntityUseCaseDecorator implements CommandHandler
{
    /**
     * @var CommandHandler
     */
    private $useCase;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(CommandHandler $useCase, EntityManagerInterface $entityManager)
    {
        $this->useCase       = $useCase;
        $this->entityManager = $entityManager;
    }

    public function handle(Command $command): DomainEventList
    {
        try {
            $this->entityManager->beginTransaction();
            $entity = $this->useCase->handle($command);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $entity;
        } catch (\Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }
    }
}
