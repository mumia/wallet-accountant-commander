<?php

namespace WalletAccountant\Projection;

use Exception;
use Prooph\Bundle\EventStore\Command\ProjectionRunCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Tests\Fixtures\DummyOutput;

/**
 * ProjectionRunner
 */
final class ProjectionRunner
{
    private const PROJECTION_NAME_PARAMETER = 'projection-name';

    /**
     * @var string
     */
    private $projectionName;

    /**
     * @var ProjectionRunCommand
     */
    private $projectionRunCommand;

    public function __construct(string $projectionName, ProjectionRunCommand $projectionRunCommand) {
        $this->projectionName = $projectionName;
        $this->projectionRunCommand = $projectionRunCommand;
    }

    /**
     * @throws Exception
     */
    public function run(): void {
        $input = new ArrayInput(
            [
                self::PROJECTION_NAME_PARAMETER => $this->projectionName,
                '--run-once' => true
            ],
            new InputDefinition(
                [
                    new InputArgument(self::PROJECTION_NAME_PARAMETER),
                    new InputOption('--run-once')
                ]
            )
        );

        $this->projectionRunCommand->run($input, new DummyOutput());
    }
}
