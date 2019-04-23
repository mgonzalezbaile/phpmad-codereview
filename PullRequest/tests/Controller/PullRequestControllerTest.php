<?php


namespace App\Tests\Controller;


use App\Entity\PullRequest;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PullRequestControllerTest extends WebTestCase
{
    public function dataProvider(): array
    {
        return [
            ['some code', 'a writer', ['a reviewer'], '2019-11-03', 11000],
            [str_pad('', 110, "\n"), 'a writer', ['a reviewer'], '2019-11-03', 12000],
            [str_pad('', 251, "\n"), 'a writer', ['a reviewer'], '2019-11-03', 12500],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testNew(string $code, string $writer, array $assignedReviewers, string $revisionDueDate, int $expectedQuote)
    {
        $client = static::createClient();
        $kernel = self::bootKernel();

        $client->request(
            'POST',
            '/pull-requests',
            [
                'code' => $code,
                'writer' => $writer,
                'assignedReviewers' => $assignedReviewers,
                'revisionDueDate' => $revisionDueDate
            ]
        );

        $response = json_decode($client->getResponse()->getContent(), true);

        /** @var EntityManager $entityManager */
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        /** @var PullRequest $pullRequest */
        $pullRequest = $entityManager
            ->getRepository(PullRequest::class)
            ->find($response['id']);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertEquals($response['id'], $pullRequest->getId());
        $this->assertEquals($code, $pullRequest->getCode());
        $this->assertEquals($writer, $pullRequest->getWriter());
        $this->assertEquals($assignedReviewers, $pullRequest->getAssignedReviewers());
        $this->assertEquals($revisionDueDate, $pullRequest->getRevisionDueDate()->format('Y-m-d'));
        $this->assertEquals($expectedQuote, $pullRequest->getQuote());
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
