<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiRoutesExistTest extends TestCase
{
    /** @test */
    public function films_index_route_exists()
    {
        $response = $this->get(route('api.films.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function films_show_route_exists()
    {
        $response = $this->get(route('api.films.show', 1));

        $response->assertStatus(200);
    }

    /** @test */
    public function planets_route_exists()
    {
        $response = $this->get(route('api.planets.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function starships_route_exists()
    {
        $response = $this->get(route('api.starships.index'));

        $response->assertStatus(200);
    }
}
