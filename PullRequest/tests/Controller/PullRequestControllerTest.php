<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PullRequestControllerTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SchemaTool
     */
    private $tool;

    public function setUp()
    {
        $kernel              = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->tool          = new SchemaTool($this->entityManager);
        $this->tool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $this->tool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }

    public function tearDown()
    {
        $this->tool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }

    public function testNew()
    {
        $client = static::createClient();

        $code              = 'aaaaaa';
        $writer            = 'asd';
        $assignedReviewers = ['reviewer1'];
        $revisionDueDate   = '2019-11-03';
        $client->request(
            'POST',
            '/pull-requests',
            [
                'code'              => $code,
                'writer'            => $writer,
                'assignedReviewers' => $assignedReviewers,
                'revisionDueDate'   => $revisionDueDate,
            ]
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function testNewFail()
    {
        $client = static::createClient();

        $kernel = self::bootKernel();

        $client->request(
            'POST',
            '/pull-requests',
            [
                'code'              => '',
                'writer'            => '123',
                'assignedReviewers' => ['reviewer1'],
                'revisionDueDate'   => '2019-01-01',
            ]
        );

        $this->assertEquals(409, $client->getResponse()->getStatusCode());
    }
}
