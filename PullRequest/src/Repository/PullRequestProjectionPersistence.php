<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PullRequestProjection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PullRequestProjection|null find($id, $lockMode = null, $lockVersion = null)
 * @method PullRequestProjection|null findOneBy(array $criteria, array $orderBy = null)
 * @method PullRequestProjection[]    findAll()
 * @method PullRequestProjection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PullRequestProjectionPersistence extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PullRequestProjection::class);
    }
}
