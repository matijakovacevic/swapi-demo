<?php

namespace App\SwApi\Models;

/**
 * @property-read string $name
 * @property-read string $height
 * @property-read string $mass
 * @property-read string $hair_color
 * @property-read string $skin_color
 * @property-read string $eye_color
 * @property-read string $birth_year
 * @property-read string $gender
 * @property-read array $homeworld
 * @property-read array $films
 * @property-read array $species
 * @property-read array $vehicles
 * @property-read array $starships
 * @property-read \Carbon\Carbon $created
 * @property-read \Carbon\Carbon $edited
 */
class Character extends BaseModel
{
    protected array $relations = [
        'homeworld',
    ];
}
