<?php

namespace App\Services\Robots;

use App\Dto\RobotDto;
use App\Services\ServiceContract;

/**
 * Class MoveService
 *
 * @package App\Services\Robots
 */
class MoveService implements ServiceContract
{
    private $compass;
    private $isXAxis;
    private $coordinate;
    private $turns;
    private $directions = ['N', 'E', 'S', 'W'];
    private $lost = false;
    private $grid;

    /**
     * @param  array  $data
     * @return string
     * @throws \Exception
     */
    public function handle(array $data)
    {
        //region Data validation
        if (!isset($data['robot']) ||
            !$data['robot'] instanceof RobotDto) {
            throw new \Exception('The invalid robot instance.');
        }

        if (!isset($data['grid'])) {
            throw new \Exception('The grid data is missing.');
        }
        //endregion Data validation

        $robot = $data['robot'];
        $this->grid = $data['grid'];

        // set the coordinate in an assoc array
        $this->coordinate = array_combine(['x', 'y'], $robot->getInitialCoordinate());

        // set the initial if-is-in-x-axis
        $this->isXAxis = $robot->getIsInXAxis();

        // set the initial turns
        $this->turns = array_search($robot->getInitialDirection(), $this->directions);

        $instructions = str_split($robot->getInstructions());

        foreach ($instructions as $instruction) {
            if ($instruction == 'F') {
                $this->move();
            } else {
                $this->turn($instruction);
            }
        }

        $lost = $this->lost ? 'LOST' : '';

        return implode(' ', $this->coordinate) . " {$this->compass} $lost";
    }

    /**
     * Updates the robot's current @property coordinate
     * @return void
     */
    private function move()
    {
        $axis = $this->isXAxis ? 'x' : 'y';

        // determine step to take relative to the grid
        // North and East are positive steps
        $addendum = strpos('NE', $this->compass) !== false ? 1 : -1;

        // update the coordinate with the new position
        $this->coordinate[$axis] = intval($this->coordinate[$axis]) + $addendum;

        // check if robot has gone off the grid
        if ($this->coordinate[$axis] > $this->grid[$axis] || $this->coordinate[$axis] < 0) {
            $this->lost = true;
        }
    }

    /**
     * Turn the robot's @property compass
     * @param $direction
     * @return void
     */
    private function turn($direction)
    {
        // a turn is always on its axis
        $this->isXAxis = !$this->isXAxis;

        // update the turn
        // positive is clockwise; negative is counterclockwise
        $this->turns += $direction == 'R' ? 1 : -1;

        // we then compute the new direction the robot is facing based on the turns taken
        // N = 0
        // E = 1 | -3
        // S = 2 | -2
        // W = 3 | -1
        $turn_direction = $this->turns % 4;

        if ($turn_direction == 0) {
            $this->compass = 'N';
        } else if ($this->turns > 0 && $turn_direction == 1) {
            $this->compass = 'E';
        } else if (abs($turn_direction) == 2) {
            $this->compass = 'S';
        } else {
            $this->compass = 'W';
        }
    }
}
