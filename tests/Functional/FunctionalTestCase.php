<?php

namespace WalletAccountant\Tests\Functional;

use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Projection\ProjectionRunner;
use WalletAccountant\Tests\Functional\Fixtures\FixturesLoader;

/**
 * FunctionalTestCase
 */
abstract class FunctionalTestCase extends WebTestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

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

        $kernel = self::bootKernel();
        $this->container = $kernel->getContainer();
        $this->fixturesLoader = $this->container->get('fixtures.loader');
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

        $this->assertTrue($client->isOkAndJson());

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

        $client = static::$kernel->getContainer()->get('walletaccountant.test.client');
        $client->setServerParameters($server);

        return $client;
    }

    /**
     * @param string $projection
     */
    protected function runProjection(string $projection): void
    {
        /** @var ProjectionRunner $projectionRunner */
        $projectionRunner = $this->container->get(sprintf('walletaccountant.projection_runner.%s', $projection));
        $projectionRunner->run();
    }
}
