<?php

declare(strict_types=1);

namespace App\Tests\UseCase;

use App\Middleware\CommandResponse;
use App\Middleware\CommonCommandHandlerBus;
use App\UseCase\Command;
use App\UseCase\DomainEvent;
use App\UseCase\Projection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use PHPUnit\Framework\Assert as PHPUnitAssert;

class UseCaseScenario extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CommonCommandHandlerBus
     */
    private $commandBus;

    /**
     * @var CommandResponse
     */
    private $commandResponse;

    /**
     * @var SchemaTool
     */
    private $tool;

    public function setUp()
    {
        $kernel              = self::bootKernel();
        $this->commandBus    = $kernel->getContainer()->get(CommonCommandHandlerBus::class);
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->tool          = new SchemaTool($this->entityManager);
        $this->tool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $this->tool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }

    public function tearDown()
    {
        $this->tool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }

    public function setUpScenario(): self
    {
        return $this;
    }

    public function withUseCase(string $useCase): self
    {
        $this->commandBus->withUseCase($useCase);

        return $this;
    }

    public function withProjectionPersistence(string $projector): self
    {
        $this->commandBus->withProjectionPersistence($projector);

        return $this;
    }

    public function given(Projection $projection): self
    {
        $this->entityManager->persist($projection);
        $this->entityManager->flush();

        return $this;
    }

    public function when(Command $command): self
    {
        $this->commandResponse = $this->commandBus->handle($command);

        return $this;
    }

    public function then(DomainEvent ...$domainEventList): self
    {
        PHPUnitAssert::assertEquals($domainEventList, $this->commandResponse->domainEventList()->asArray());

        return $this;
    }

    public function andProjections(Projection ...$expectedProjections): self
    {
        PHPUnitAssert::assertEquals($expectedProjections, $this->commandResponse->projectionList()->asArray());

        return $this;
    }
}
