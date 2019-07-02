<?php

declare(strict_types=1);

namespace App\Tests\UseCase;

use App\Entity\PullRequest;
use App\Event\QuoteCalculated;
use App\Repository\PullRequestRepository;
use App\UseCase\CalculateQuoteCommand;
use App\UseCase\CalculateQuoteUseCase;

class CalculateQuoteUseCaseTest extends UseCaseScenario
{
    /**
     * @dataProvider provider
     */
    public function testShouldCalculateQuote($id, $code, $assignedReviewers, $revisionDueDate, $expectedQuote)
    {
        $this
            ->setUpScenario()
            ->withUseCase(CalculateQuoteUseCase::class)
            ->withRepository(PullRequestRepository::class);

        $aPullRequest = (new PullRequest())->withId($id);
        $this
            ->given(
                $aPullRequest
            )
            ->when(
                new CalculateQuoteCommand($id, $code, $revisionDueDate, $assignedReviewers)
            )
            ->then(
                new QuoteCalculated($id, $expectedQuote)
            );
    }

    public function provider(): array
    {
        return [
            ['some id', 'aaaa', [1], '2019-11-03', 11000],
            ['some id', str_pad('', 110, "\n"), [1], '2019-11-03', 12000],
            ['some id', str_pad('', 251, "\n"), [1], '2019-11-03', 12500],
            ['some id', str_pad('', 501, "\n"), [1], '2019-11-03', 15000],
            ['some id', str_pad('', 1001, "\n"), [1], '2019-11-03', 20000],
            ['some id', str_pad('', 1001, "\n"), [1, 2, 3], '2019-11-03', 40000],
            ['some id', str_pad('', 1001, "\n"), [1, 2, 3], $this->addInterval(10), 40000],
            ['some id', str_pad('', 1001, "\n"), [1, 2, 3], $this->addInterval(6), 40025],
            ['some id', str_pad('', 1001, "\n"), [1, 2, 3], $this->addInterval(4), 45000],
            ['some id', str_pad('', 1001, "\n"), [1, 2, 3], $this->addInterval(1), 50000],
        ];
    }

    private function addInterval(int $days): string
    {
        return (new \DateTimeImmutable())->add(\DateInterval::createFromDateString((string) $days . 'days'))->format('Y-m-d');
    }
}
