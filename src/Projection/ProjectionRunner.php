<?php

namespace WalletAccountant\Projection;

use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ProjectionManager;
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
        $projector = $projectionManager->createReadModelProjection($projectionName, $readModel);
        $this->projection = $projection->project($projector);
    }

    public function run(): void
    {
        $this->projection->run(false);
    }
}
