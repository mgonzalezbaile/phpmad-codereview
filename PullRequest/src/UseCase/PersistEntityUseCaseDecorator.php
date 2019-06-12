<?php


namespace App\UseCase;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class PersistEntityUseCaseDecorator implements IExecuteCommand
{
    /**
     * @var IExecuteCommand
     */
    private $useCase;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(IExecuteCommand $useCase, EntityManagerInterface $entityManager)
    {
        $this->useCase = $useCase;
        $this->entityManager = $entityManager;
    }

    public function execute(Command $command)
    {
        try {
            $this->entityManager->beginTransaction();
            $entity = $this->useCase->execute($command);

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
