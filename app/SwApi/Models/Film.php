<?php

namespace App\SwApi\Models;

class Film extends BaseModel
{
    protected array $relations = [
        'characters'
    ];
}
