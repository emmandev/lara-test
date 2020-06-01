<?php

namespace App\Services\Instructions;

use App\Dto\InstructionDto;
use App\Dto\RobotDto;
use App\Services\ServiceContract;

class InitializeService implements ServiceContract
{
    /**
     * @var InstructionDto
     */
    private $instructionDto;

    public function __construct(InstructionDto $instructionDto)
    {
        $this->instructionDto = $instructionDto;
    }

    public function handle(array $data)
    {
        // initialize data
        $instruction = $this->instructionDto->fill($data['input']);

        // initialize robots
        $robots = [];

        foreach ($instruction->getRobotInstructions() as $robot) {
            $robots[] = (new RobotDto())->fill([
                'coordinate' => $robot[0],
                'instructions' => $robot[1],
            ]);
        }

        return compact('instruction');
    }
}
