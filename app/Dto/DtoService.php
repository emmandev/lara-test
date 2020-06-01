<?php

namespace App\Dto;

interface DtoService
{
    public function fill($data) : self;
}
