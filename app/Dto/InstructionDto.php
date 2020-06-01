<?php

namespace App\Dto;

/**
 * Class InstructionDto
 *
 * @package App\Dto
 */
class InstructionDto implements DtoService
{
    private $grid;
    private $robotInstructions;

    /**
     * @param $data
     * @return DtoService
     * @throws \Exception
     */
    public function fill($data) : DtoService
    {
        if (!is_string($data)) {
            throw new \Exception('The instruction input must be a string.');
        }

        if (empty($data)) {
            throw new \Exception('The instruction input cannot be empty.');
        }

        // destructure instruction
        $data = explode("\n", $data);

        // get grid size
        $grid = array_shift($data);

        [$gridX, $gridY] = explode(' ', $grid);

        if (!is_numeric($gridX)) {
            throw new \Exception('The grid size is invalid.');
        }

        if (!is_numeric($gridY)) {
            throw new \Exception('The grid size is invalid.');
        }

        if ($gridX > config('instructions.grid.max_size') ||
            $gridY > config('instructions.grid.max_size')) {
            throw new \Exception('The grid size is too big.');
        }

        if (count($data) % 2 !== 0) {
            throw new \Exception('Invalid robot instructions.');
        }

        $this->grid = ['x' => $gridX, 'y' => $gridY];
        $this->robotInstructions = array_chunk($data, 2);

        return $this;
    }

    /**
     * @return array|null
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * @return array|null
     */
    public function getRobotInstructions()
    {
        return $this->robotInstructions;
    }
}
