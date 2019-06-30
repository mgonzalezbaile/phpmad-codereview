<?php

declare(strict_types=1);

namespace App\Tests\UseCase;

use App\UseCase\CalculateQuoteCommand;
use App\UseCase\CalculateQuoteUseCase;
use PHPUnit\Framework\TestCase;

class CalculateQuoteUseCaseTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testShouldCalculateQuote($code, $assignedReviewers, $revisionDueDate, $expectedQuote)
    {
        $useCase = new CalculateQuoteUseCase();

        $quote = $useCase->handle(
            new CalculateQuoteCommand(
                $code,
                $revisionDueDate,
                $assignedReviewers
            )
        );

        $this->assertEquals($expectedQuote, $quote);
    }

    public function provider(): array
    {
        return [
            ['aaaa', [1], '2019-11-03', 11000],
            [str_pad('', 110, "\n"), [1], '2019-11-03', 12000],
            [str_pad('', 251, "\n"), [1], '2019-11-03', 12500],
            [str_pad('', 501, "\n"), [1], '2019-11-03', 15000],
            [str_pad('', 1001, "\n"), [1], '2019-11-03', 20000],
            [str_pad('', 1001, "\n"), [1, 2, 3], '2019-11-03', 40000],
            [str_pad('', 1001, "\n"), [1, 2, 3], $this->addInterval(10), 40000],
            [str_pad('', 1001, "\n"), [1, 2, 3], $this->addInterval(6), 40025],
            [str_pad('', 1001, "\n"), [1, 2, 3], $this->addInterval(4), 45000],
            [str_pad('', 1001, "\n"), [1, 2, 3], $this->addInterval(1), 50000],
        ];
    }

    private function addInterval(int $days): string
    {
        return (new \DateTimeImmutable())->add(\DateInterval::createFromDateString((string) $days . 'days'))->format('Y-m-d');
    }
}
