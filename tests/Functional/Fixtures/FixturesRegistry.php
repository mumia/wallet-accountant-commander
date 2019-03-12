<?php

namespace WalletAccountant\Tests\Functional\Fixtures;

use function array_keys;
use function sprintf;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * FixturesRegistry
 */
class FixturesRegistry
{
    /**
     * @var array
     */
    private $fixtures;

    /**
     * @param array $fixtures
     */
    public function __construct(array $fixtures)
    {
        foreach ($fixtures as $fixture) {
            if (!$fixture instanceof AbstractFixture) {
                throw new InvalidArgumentException('Fixture is not of the correct type');
            }
        }

        $this->fixtures = $fixtures;
    }

    /**
     * @param string $key
     *
     * @return AbstractFixture
     */
    public function get(string $key): AbstractFixture
    {
        if (!isset($this->fixtures[$key])) {
            throw new InvalidArgumentException(sprintf('No fixture set with key "%s"', $key));
        }

        return $this->fixtures[$key];
    }

    /**
     * @return array
     */
    public function getKeys(): array
    {
        return array_keys($this->fixtures);
    }
}
