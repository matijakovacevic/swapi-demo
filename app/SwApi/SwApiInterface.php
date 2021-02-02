<?php

namespace App\SwApi;

use Carbon\Carbon;
use App\SwApi\Models\Film;
use Illuminate\Support\Collection;

interface SwApiInterface
{
    public function api(): SwApiClient;
    public function getFilms(): Collection;
    public function getFilmById(int $id): Film;
    public function getStarshipsWithMinimalPassengersCount(int $count): Collection;
    public function getPlanetsCreatedAfter(Carbon $date): Collection;
}
