<?php

namespace WalletAccountant\Tests\Functional;

use Doctrine\DBAL\DBALException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use WalletAccountant\Projection\ProjectionRunner;
use WalletAccountant\Tests\Functional\Fixtures\FixturesLoader;

/**
 * FunctionalTestCase
 */
abstract class FunctionalTestCase extends WebTestCase
{
    /**
     * @var FixturesLoader
     */
    private $fixturesLoader;

    /**
     * @param string|null $name
     * @param array       $data
     * @param string      $dataName
     */
    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        self::bootKernel();
        $this->fixturesLoader = self::$container->get('fixtures.loader');
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        self::bootKernel();
    }

    /**
     * @throws DBALException
     */
    public function loadFixtures(): void
    {
        $this->fixturesLoader->loadAll();
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @return Response
     */
    public function login(string $email, string $password): Response
    {
        $client = self::createClient();
        $client->post('/login', ['email' => $email, 'password' => $password]);

        $this->assertTrue($client->isOkAndJson(), $client->getContent());

        return $client->getResponse();
    }

    /**
     * @param array $options
     * @param array $server
     *
     * @return TestClient
     */
    protected static function createClient(array $options = [], array $server = []): TestClient
    {
        static::bootKernel($options);

        /** @var TestClient $client */
        $client = static::$kernel->getContainer()->get('walletaccountant.test.client');
        $client->setServerParameters($server);

        return $client;
    }

    /**
     * @param string $projection
     * @throws Exception
     */
    protected function runProjection(string $projection): void
    {
        /** @var ProjectionRunner $projectionRunner */
        $projectionRunner = self::$container->get(sprintf('walletaccountant.projection_runner.%s', $projection));
        $projectionRunner->run();
    }
}
