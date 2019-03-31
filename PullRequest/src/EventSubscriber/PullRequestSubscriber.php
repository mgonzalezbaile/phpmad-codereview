<?php

namespace App\EventSubscriber;

use App\Service\KataMailerService;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\PullRequest;
use Doctrine\ORM\Events;

class PullRequestSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof PullRequest) {
            $entity->setUpdatedAt(new \DateTime());
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof PullRequest) {
            $entity->setUpdatedAt(new \DateTime());
            $quote = 0;
            $codeLines = substr_count( $entity->getCode(), "\n" );

            if($codeLines > 100){
                if($codeLines > 1000){
                    $quote += 10000;
                } else if ($codeLines > 500) {
                    $quote += 5000;
                } else if ($codeLines > 250) {
                    $quote += 2500;
                } else {
                    $quote += 2000;
                }
            } else {
                $quote += 1000;
            }

            $revisionDueDate = $entity->getRevisionDueDate();
            $today = new \DateTimeImmutable();
            $daysLeft = $revisionDueDate->diff($today)->format('%a');
            if($daysLeft < 2){
                $quote += 10000;
            } else if ($daysLeft < 5){
                $quote += 5000;
            } else if ($daysLeft < 7){
                $quote += 25;
            }

            foreach ( $entity->getAssignedReviewers() as $assignedReviewer )
            {
                $quote += 10000;
            }

            $entity->setQuote($quote);
        }
    }
}
