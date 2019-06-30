<?php

declare(strict_types=1);

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class                => ['all' => true],
    Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle::class       => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class                 => ['all' => true],
    Symfony\Bundle\MakerBundle\MakerBundle::class                        => ['dev' => true],
    Symfony\Bundle\WebServerBundle\WebServerBundle::class                => ['dev' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class                    => ['all' => true],
];
