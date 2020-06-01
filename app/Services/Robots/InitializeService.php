<?php

namespace App\Services\Robots;

use App\Dto\RobotDto;
use App\Services\ServiceContract;

class InitializeService implements ServiceContract
{
    /**
     * @var MoveService
     */
    private $moveService;

    public function __construct(MoveService $moveService)
    {
        $this->moveService = $moveService;
    }

    public function handle(array $data)
    {
        if (!isset($data['robots']) ||
            !$data['robots']) {
            throw new \Exception('The invalid robot instance.');
        }

        $robots = $data['robots'];
        $output = [];

        foreach ($robots as $robot) {
            $robot = $this->createRobot($robot);
            $output[] = $this->moveService->handle([
                'robot' => $robot,
                'grid' => $data['grid'],
            ]);
        }

        return implode("\n", $output);
    }

    public function createRobot($robot)
    {
        return (new RobotDto())->fill([
            'coordinate' => $robot[0],
            'instructions' => $robot[1],
        ]);
    }
}
