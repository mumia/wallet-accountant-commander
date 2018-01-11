<?php

namespace WalletAccountant\Projection;

use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ProjectionManager;
use Prooph\EventStore\Projection\Projector;
use Prooph\EventStore\Projection\ReadModel;

/**
 * ProjectionRunner
 */
final class ProjectionRunner
{
    /**
     * @var ReadModelProjection
     */
    private $projection;

    /**
     * @var Projector
     */
    private $projector;

    /**
     * @param string              $projectionName
     * @param ReadModelProjection $projection
     * @param ProjectionManager   $projectionManager
     * @param ReadModel           $readModel
     */
    public function __construct(
        string $projectionName,
        ReadModelProjection $projection,
        ProjectionManager $projectionManager,
        ReadModel $readModel
    ) {
        $this->projection = $projection;
        $this->projector = $projectionManager->createReadModelProjection($projectionName, $readModel);
    }

    public function run(): void
    {
        $projection = $this->projection->project($this->projector);
        $projection->run(false);
    }
}
