<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Testing\TestResponse;

class ApiRoutesTest extends TestCase
{
    /** @test */
    public function films_index_route_returns_proper_data(): void
    {
        $response = $this->getJson(route('api.films.index'));

        $response->assertStatus(200);

        $this->assertValidPaginatorStructure($response);
        $this->assertJsonStructureForPaginatedResponse([
            'data' => [
                [
                    "title",
                    "episode_id",
                    "opening_crawl",
                    "director",
                    "producer",
                    "release_date",
                    "characters",
                    "planets",
                    "starships",
                    "vehicles",
                    "species",
                    "created",
                    "edited",
                    "url",
                ],
            ],
        ], $response);
    }

    /** @test */
    public function films_show_route_returns_proper_data(): void
    {
        $response = $this->getJson(route('api.films.show', 1));

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'species');
        $response->assertJsonCount(4, 'vehicles');
        $response->assertJsonCount(8, 'starships');
        $response->assertJsonCount(3, 'planets');
        $response->assertJsonCount(18, 'characters');

        $response->assertJsonPath('title', 'A New Hope');
        $response->assertJsonPath('episode_id', 4);
        $response->assertJsonPath('characters.11.name', 'Chewbacca');
        $response->assertJsonPath('starships.6.name', 'X-wing');
    }

    /** @test */
    public function validation_error_response_when_films_filter_by_character_not_specific_enough(): void
    {
        $response = $this->getJson(route('api.films.index', [
            'filter' => [
                'character' => 'skywalker'
            ],
        ]));

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['filter.character']);
    }

    /** @test */
    public function films_filtered_by_character_returns_correct_films(): void
    {
        $response = $this->getJson(route('api.films.index', [
            'filter' => [
                'character' => 'anakin skywalker'
            ],
        ]));

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');

        $this->assertEquals(
            Collection::make($response->json('data'))->pluck('title')->toArray(),
            [
                "The Phantom Menace",
                "Attack of the Clones",
                "Revenge of the Sith",
            ]
        );
    }

    /** @test */
    public function planets_index_route_returns_proper_data(): void
    {
        $response = $this->getJson(route('api.planets.index'));

        $response->assertStatus(200);

        $this->assertValidPaginatorStructure($response);
        $this->assertJsonStructureForPaginatedResponse([
            'data' => [
                [
                    "name",
                    "rotation_period",
                    "orbital_period",
                    "diameter",
                    "climate",
                    "gravity",
                    "terrain",
                    "surface_water",
                    "population",
                    "residents",
                    "films",
                    "created",
                    "edited",
                    "url",
                ],
            ],
        ], $response);
    }

    /** @test */
    public function planets_filtered_by_create_date_return_proper_data(): void
    {
        $response = $this->getJson(route('api.planets.index', [
            'filter' => [
                'created' => '12/20/2014'
            ],
        ]));

        $response->assertJsonPath('total', 24);
        $response->assertJsonPath('data.0.name', 'Ryloth');
    }

    /** @test */
    public function starships_filtered_by_passengers_number_return_proper_data(): void
    {
        $response = $this->getJson(route('api.starships.index', [
            'filter' => [
                'passengers' => '84000'
            ],
        ]));

        $response->assertJsonPath('total', 3);
        $response->assertJsonPath('data.0.name', 'Death Star');
    }

    /** @test */
    public function starships_index_route_returns_proper_data(): void
    {
        $response = $this->getJson(route('api.starships.index'));

        $response->assertStatus(200);
        $this->assertValidPaginatorStructure($response);
        $this->assertJsonStructureForPaginatedResponse([
            'data' => [
                [
                    "name",
                    "model",
                    "manufacturer",
                    "cost_in_credits",
                    "length",
                    "max_atmosphering_speed",
                    "crew",
                    "passengers",
                    "cargo_capacity",
                    "consumables",
                    "hyperdrive_rating",
                    "MGLT",
                    "starship_class",
                    "pilots",
                    "films",
                    "created",
                    "edited",
                    "url",
                ],
            ],
        ], $response);
    }

    private function assertJsonStructureForPaginatedResponse(array $structure, TestResponse $response): void
    {
        $totalCount = (int) $response->json('total');

        $this->assertGreaterThanOrEqual(0, $totalCount);
        if ($totalCount) {
            $response->assertJsonStructure($structure);
        }
    }

    private function assertValidPaginatorStructure(TestResponse $response): void
    {
        $response->assertJsonStructure([
            'current_page',
            'data',
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ]);
    }
}
