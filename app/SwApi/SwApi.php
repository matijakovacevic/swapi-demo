<?php

namespace App\SwApi;

use Carbon\Carbon;
use App\SwApi\Models\Film;
use App\SwApi\Models\Planet;
use App\SwApi\Models\Starship;
use App\SwApi\Models\Character;

class SwApi implements SwApiInterface
{
    protected SwApiClient $client;

    public function __construct(SwApiClient $apiClient)
    {
        $this->client = $apiClient;
    }

    public function api(): SwApiClient
    {
        return $this->client;
    }

    public function getFilms(): Collection
    {
        return $this->client->films()->getAllResults();
    }

    public function getPlanets(): Collection
    {
        return $this->client->planets()->getAllResults();
    }

    public function getStarships(): Collection
    {
        return $this->client->starships()->getAllResults();
    }

    public function getFilmById(int $id): Film
    {
        return new Film($this->client->films($id)->get());
    }

    public function getCharacterByName(string $characterName): Character
    {
        $result = $this->client->people()->searchFor($characterName)->getResults();

        $resultCount = Collection::make($result)
                                 ->keys()
                                 ->filter(function ($value) {
                                     return is_int($value);
                                 })->count();

        // if all keys are strings, a character is returned, otherwise multiple characters are returned
        $resultCount = $resultCount ?: 1;

        throw_if(
            $resultCount > 1,
            new \LogicException('Character filter must be more specific and return exactly one result!')
        );

        return new Character($result);
    }

    public function getFilmsFromCharacter(Character $character): array
    {
        return $character->resolveRelations(['films'])['films'];
    }

    public function getStarshipsWithMinimalPassengersCount(int $count): Collection
    {
        return $this->client->starships()
                            ->getAllResults(function (array $results) use ($count) {
                                 return Collection::make($results)
                                     ->mapInto(Starship::class)
                                     ->filter
                                     ->hasMorePassengersThan($count);
                            });
    }

    public function getPlanetsCreatedAfter(Carbon $date): Collection
    {
        return $this->client->planets()
                            ->getAllResults(function (array $results) use ($date) {
                                 return Collection::make($results)
                                     ->mapInto(Planet::class)
                                     ->filter
                                     ->createdAfter($date);
                            });
    }
}
