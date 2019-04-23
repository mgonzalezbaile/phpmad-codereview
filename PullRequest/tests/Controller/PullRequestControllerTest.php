<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PullRequestControllerTest extends WebTestCase
{
    public function testNew()
    {
        $client = static::createClient();

        $kernel = self::bootKernel();

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
        $objectManager = $client->getContainer()->get(ObjectManager::class);
        $this->assertEquals(true, $objectManager->isPersistCalled());
        $this->assertEquals(true, $objectManager->isFlushCalled());
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
                'assignedReviewers' => [1],
                'revisionDueDate'   => '2019-01-01',
            ]
        );

        $this->assertEquals(409, $client->getResponse()->getStatusCode());
    }
}
