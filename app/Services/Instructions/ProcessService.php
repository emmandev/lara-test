<?php

namespace App\Services\Instructions;

use App\Services\Robots\InitializeService as InitializeRobotsService;
use App\Services\ServiceContract;

/**
 * Class ProcessService
 *
 * @package App\Services\Instructions
 */
class ProcessService implements ServiceContract
{
    /**
     * @var InitializeService
     */
    private $initializeService;

    /**
     * @var InitializeRobotsService
     */
    private $initializeRobotsService;

    /**
     * ProcessService constructor.
     * @param  InitializeService  $initializeService
     * @param  InitializeRobotsService  $initializeRobotsService
     */
    public function __construct(InitializeService $initializeService, InitializeRobotsService $initializeRobotsService)
    {
        $this->initializeService = $initializeService;
        $this->initializeRobotsService = $initializeRobotsService;
    }

    /**
     * @param  array  $data
     * @return array
     * @throws \Exception
     */
    public function handle(array $data)
    {
        // initialize grid and robots
        $data = $this->initializeService->handle($data);

        // process robots
        $robots = $this->initializeRobotsService->handle([
            'robots' => $data['instruction']->getRobotInstructions(),
            'grid' => $data['instruction']->getGrid(),
        ]);

        return compact('robots');
    }
}
