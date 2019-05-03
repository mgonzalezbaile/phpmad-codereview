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
        }
    }
}
