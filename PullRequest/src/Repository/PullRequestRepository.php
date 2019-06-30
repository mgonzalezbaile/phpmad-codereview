<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PullRequestProjection;
use App\UseCase\PullRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PullRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method PullRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method PullRequest[]    findAll()
 * @method PullRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PullRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PullRequestProjection::class);
    }

    public function ofId($id): ?PullRequest
    {
        return $this->find($id);
    }
}
