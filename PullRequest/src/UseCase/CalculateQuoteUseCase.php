<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Event\QuoteCalculated;

class CalculateQuoteUseCase implements CommandHandler
{
    /**
     * @param CalculateQuoteCommand $command
     *
     * @return DomainEventList
     */
    public function handle(Command $command): DomainEventList
    {
        $quote     = 0;
        $codeLines = substr_count($command->code(), "\n");

        if ($codeLines > 100) {
            if ($codeLines > 1000) {
                $quote += 10000;
            } elseif ($codeLines > 500) {
                $quote += 5000;
            } elseif ($codeLines > 250) {
                $quote += 2500;
            } else {
                $quote += 2000;
            }
        } else {
            $quote += 1000;
        }

        $revisionDueDate = \DateTimeImmutable::createFromFormat('Y-m-d', $command->revisionDueDate());
        $today           = new \DateTimeImmutable();
        $daysLeft        = $revisionDueDate->diff($today)->format('%a');
        if ($daysLeft < 2) {
            $quote += 10000;
        } elseif ($daysLeft < 5) {
            $quote += 5000;
        } elseif ($daysLeft < 7) {
            $quote += 25;
        }

        foreach ($command->assignedReviewers() as $assignedReviewer) {
            $quote += 10000;
        }

        return DomainEventList::fromDomainEvents(new QuoteCalculated($command->id(), $quote));
    }
}
