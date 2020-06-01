<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class BaseController extends Controller
{
    /**
     * @var Validator
     */
    protected $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }
}
