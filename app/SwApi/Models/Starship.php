<?php

namespace App\SwApi\Models;

/**
 * @property-read string $name
 * @property-read string $model
 * @property-read string $manufacturer
 * @property-read string $cost_in_credits
 * @property-read string $length
 * @property-read string $max_atmosphering_speed
 * @property-read string $crew
 * @property-read string $passengers
 * @property-read string $cargo_capacity
 * @property-read string $consumables
 * @property-read string $hyperdrive_rating
 * @property-read string $MGLT
 * @property-read string $starship_class
 * @property-read array $pilots
 * @property-read array $films
 * @property-read \Carbon\Carbon $created
 * @property-read \Carbon\Carbon $edited
 */
class Starship extends BaseModel
{
    protected array $relations = [
        'pilots',
    ];

    public function hasMorePassengersThan(int $count): bool
    {
        $passengers = (int)filter_var($this->passengers, FILTER_SANITIZE_NUMBER_INT);
        $crew = (int)filter_var($this->crew, FILTER_SANITIZE_NUMBER_INT);

        $passengersInTotal = $passengers + $crew;

        return $passengersInTotal > $count;
    }
}
