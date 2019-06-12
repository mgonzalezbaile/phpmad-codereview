<?php


namespace App\Tests;


use PHPUnit\Framework\TestCase;
use Throwable;

class DecoratorTest extends TestCase
{
    public function testDecorator()
    {
        $useCase = new UseCaseDbTransactionDecorator(new UseCaseTimerDecorator(new SomeUseCase()));

        $useCase->execute();

        $this->assertTrue(True);
    }
}

interface UseCaseInterface
{
    public function execute(): void;
}

class SomeUseCase implements UseCaseInterface
{

    public function execute(): void
    {
        echo "CHECK SOMETHING\n";
//        throw new \Exception();
        echo "DO SOMETHING\n";
    }
}

class UseCaseTimerDecorator implements UseCaseInterface
{
    /**
     * @var UseCaseInterface
     */
    private $decoratedUseCase;

    public function __construct(UseCaseInterface $decoratedUseCase)
    {
        $this->decoratedUseCase = $decoratedUseCase;
    }

    public function execute(): void
    {
        echo "START TIME: " . time() . "\n";
        $this->decoratedUseCase->execute();
        echo "END TIME: " . time() . "\n";
    }
}

class UseCaseDbTransactionDecorator implements UseCaseInterface
{
    /**
     * @var UseCaseInterface
     */
    private $decoratedUseCase;

    public function __construct(UseCaseInterface $decoratedUseCase)
    {
        $this->decoratedUseCase = $decoratedUseCase;
    }

    public function execute(): void
    {
        try {
            echo "INIT DB TRANSACTION\n";
            $this->decoratedUseCase->execute();
            echo "COMMIT DB TRANSACTION\n";

        } catch (Throwable $exception) {
            echo "ROLLBACK DB TRANSACTION\n";
        }
    }
}
