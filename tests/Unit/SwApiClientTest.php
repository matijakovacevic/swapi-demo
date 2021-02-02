<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\SwApi\SwApiClient;
use Illuminate\Http\Client\Factory;

/**
 * Tests api response types, statuses and query parameters
 */
class SwApiClientTest extends TestCase
{
    protected SwApiClient $api;

    protected function setUp(): void
    {
        parent::setUp();

        $this->api = new SwApiClient($this->app->make(Factory::class));
    }

    /** @test */
    public function endpoint() {
        $response = $this->api->endpoint('people')->getResponse();

        $this->assertEquals('/api/people/', $response->effectiveUri()->getPath());
    }

    /** @test */
    public function searchFor() {
        $searchString = 'some search string';
        $response = $this->api->searchFor($searchString)->endpoint('some-endpoint')->getResponse();

        $uri = $response->effectiveUri();

        $this->assertEquals($uri->getPath(), '/api/some-endpoint');
        $this->assertEquals($uri->getQuery(), 'search=some%20search%20string');
    }

    /** @test */
    public function filterPage() {
        $page = 3;
        $response = $this->api->filterPage($page)->endpoint('some-endpoint')->getResponse();

        $uri = $response->effectiveUri();

        $this->assertEquals($uri->getPath(), '/api/some-endpoint');
        $this->assertEquals($uri->getQuery(), 'page=3');
    }

    /**
     * Assert that the api client responds with a json response
     *
     * @test
     */
    public function swApiGetMethod() {
        $response = $this->api->endpoint('people/1')->getResponse();

        $this->assertJson($response);
    }
}
