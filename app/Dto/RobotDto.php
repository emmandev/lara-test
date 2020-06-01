<?php

namespace App\Dto;

/**
 * Class RobotDto
 *
 * @package App\Dto
 */
class RobotDto implements DtoService
{
    private $initialCoordinate;
    private $initialDirection;
    private $instructions;
    private $isInXAxis;

    /**
     * @param $data
     * @return DtoService
     * @throws \Exception
     */
    public function fill($data): DtoService
    {
        //region Data validation
        if (!isset($data['instructions'])) {
            throw new \Exception('The robot must have instructions.');
        }

        if (!preg_match(config('instructions.robots.instruction_regex'), $data['instructions'])) {
            throw new \Exception('The robot has invalid instructions.');
        }

        if (strlen($data['instructions']) > config('instructions.robots.max_instructions')) {
            throw new \Exception('The robot instruction is over the limit.');
        }

        if (!isset($data['coordinate'])) {
            throw new \Exception('The robot must have an initial coordinate.');
        }

        [$coordinate, $direction] = array_chunk(explode(' ', $data['coordinate']), 2);

        $direction[0] = strtoupper($direction[0]);

        if (!preg_match('/^[NSWE]$/',   $direction[0])) {
            throw new \Exception('The robot has invalid direction.');
        }
        //endregion Data validation

        $this->isInXAxis = strpos('WE', $direction[0]) !== false;
        $this->initialCoordinate = $coordinate;
        $this->initialDirection = $direction[0];
        $this->instructions = strtoupper($data['instructions']);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInitialCoordinate()
    {
        return $this->initialCoordinate;
    }

    /**
     * @return string|null
     */
    public function getInitialDirection()
    {
        return $this->initialDirection;
    }

    /**
     * @return string|null
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * @return bool|null
     */
    public function getIsInXAxis()
    {
        return $this->isInXAxis;
    }
}
