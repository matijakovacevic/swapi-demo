<?php

namespace App\SwApi\Models;

use Carbon\Carbon;

class Planet extends BaseModel
{
    protected array $relations = [
        'residents',
    ];

    public function createdAfter(Carbon $date): bool
    {
        return Carbon::make($this->created)->greaterThanOrEqualTo($date);
    }
}
