<?php

namespace App\SwApi;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;
use App\Exceptions\InvalidUrlException;

class SwApiClient implements SwApiClientInterface
{
    public const ENDPOINT_PEOPLE = 'people';
    public const ENDPOINT_FILMS = 'films';
    public const ENDPOINT_PLANETS = 'planets';
    public const ENDPOINT_STARSHIPS = 'starships';

    protected Factory $http;

    protected string $url;

    protected string $search;
    protected ?int $page;
    protected string $endpoint;
    protected array $endpointQuery;

    public function __construct(Factory $http)
    {
        $this->url = 'http://swapi.dev/api';
        $this->resetFields();

        $this->http = $http;
    }

    /**
     * Search parameter
     *
     * @see  https://swapi.dev/documentation#search
     */
    public function searchFor(string $string): self
    {
        $this->search = $string;

        return $this;
    }

    /**
     * Filter parameter to return a specific part of the paginated result
     */
    public function filterPage(int $number): self
    {
        $this->page = $number;

        return $this;
    }

    /**
     * Sets endpoint and query string parameters for the given endpoint
     *
     * @param string $url
     * @param array  $query
     */
    public function endpoint(string $url, array $query = []): self
    {
        $this->endpoint = $url;
        $this->endpointQuery = $query;

        return $this;
    }

    /**
     * Alias for setting planets endpoint
     */
    public function planets(array $query = []): self
    {
        return $this->endpoint(self::ENDPOINT_PLANETS, $query);
    }

    /**
     * @param int|null $id If given, returns an film model endpoint.
     *                     Otherwise, returns films root endpoint.
     *
     * Alias for setting films endpoint
     */
    public function films(int $id = null, array $query = []): self
    {
        $args = func_get_args();
        $endpoint = self::ENDPOINT_FILMS;

        $id = is_int($args[0] ?? null) ? $args[0] : null;

        if ($id) {
            $id = $args[0];
            $query = is_array($args[1] ?? null) ? $args[1] : [];

            $endpoint .= $id ? "/$id" : '';
        }
        else {
            $query = $args[0] ?? [];
        }

        return $this->endpoint($endpoint, $query);
    }

    /**
     * Alias for setting starships endpoint
     */
    public function starships(array $query = []): self
    {
        return $this->endpoint(self::ENDPOINT_STARSHIPS, $query);
    }

    /**
     * Alias for setting people endpoint
     */
    public function people(array $query = []): self
    {
        return $this->endpoint(self::ENDPOINT_PEOPLE, $query);
    }

    /**
     * @param callable|null $callback Loops through endpoint pagination. If no callback provided, returns all results.
     *                                Otherwise returns only filtered results.
     *
     * @return \App\SwApi\Collection
     * @throws \Throwable
     */
    public function getAllResults(callable $callback = null): Collection
    {
        $matchingCollection = Collection::make();

        do {
            $response = $this->get();

            if (is_null($callback)) {
                $data = $response['results'];
            }
            else {
                $data = $callback($response['results']);
            }

            throw_if(
                !($data instanceof Collection) && !is_array($data),
                new \InvalidArgumentException('Data returned not valid! Can be either a collection or array')
            );

            $matchingCollection = $matchingCollection->merge($data);

            $this->endpoint = $response['next'] ?? '';
        } while (!is_null($response['next']));

        return $matchingCollection;
    }

    public function getResults(string $fullUrl = null)
    {
        $results = $this->get($fullUrl);

        return ($results['count'] ?? null) === 1
            ? $results['results'][0]
            : $results['results'];
    }


    /**
     * If given, it queries the url directly, instead of building endpoint url
     *
     * @param string|null $fullUrl If given, it queries the url directly, instead of building endpoint url
     *
     * @return array|mixed
     * @throws \Throwable
     */
    public function get(string $fullUrl = null)
    {
        $result = $this->getResponse($fullUrl)->json();

        $this->resetFields();

        return $result;
    }

    /**
     * Returns the response object of the api query
     *
     * @param string|null $fullUrl If given, it queries the url directly, instead of building endpoint url
     *
     * @return \Illuminate\Http\Client\Response
     * @throws \Throwable
     */
    public function getResponse(string $fullUrl = null): Response
    {
        if ($fullUrl) {
            throw_if(!$this->isUrlValid($fullUrl), new InvalidUrlException('Provided string is not a valid url!'));

            return $this->http->get($fullUrl);
        }

        [$segment, $queryFromSegment] = $this->parseUrl($this->endpoint);

        $query = array_merge(
            $this->endpointQuery,
            $queryFromSegment,
            $this->search ? ['search' => $this->search] : [],
            $this->page ? ['page' => $this->page] : [],
        );

        return $this->http->get($this->url . '/' . $segment, $query);
    }

    /**
     * Checks if url string is valid
     */
    protected function isUrlValid(string $string): bool
    {
        return filter_var($string, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Parses the url and the query part of the url string
     */
    protected function parseUrl(string $url): array
    {
        ['path' => $path, 'query' => $query] = parse_url($url) + ['query' => ''];

        $path = str_replace('api/', '', $path);
        $path = trim($path, '/');

        parse_str($query, $query);

        return [
            $path,
            $query,
        ];
    }

    /**
     * Resets the parameters for new api query
     */
    protected function resetFields(): void
    {
        $this->search = '';
        $this->page = null;
        $this->endpoint = '';
        $this->endpointQuery = [];
    }
}
