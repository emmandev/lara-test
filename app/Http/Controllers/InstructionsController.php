<?php

namespace App\Http\Controllers;

use App\Services\Instructions\ProcessService;

/**
 * Class InstructionsController
 *
 * @package App\Http\Controllers
 */
class InstructionsController extends BaseController
{
    /**
     * @param  ProcessService  $processService
     * @return string|void
     * @throws \Exception
     */
    public function process(ProcessService $processService)
    {
        $data = request()->all();

        $validation = $this->validator::make($data, [
            'input' => 'required|string',
        ]);

        if ($validation->fails()) {
            return response(['error' => 'invalid data'], 400);
        }

        $response = $processService->handle($data);
        return response($response, 200);
    }
}
