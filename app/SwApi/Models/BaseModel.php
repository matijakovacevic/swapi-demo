<?php

namespace App\SwApi\Models;

use Carbon\Carbon;
use App\SwApi\SwApiClient;
use Illuminate\Contracts\Support\Arrayable;

abstract class BaseModel implements Arrayable
{
    protected array $data;
    protected array $baseRelations = [
        'people',
        'films',
        'starships',
        'vehicles',
        'species',
        'planets',
    ];
    protected array $resolvedRelations = [];

    public function __construct(array $data)
    {
        $this->data = $this->prepareData($data);
    }

    /**
     * Resolve model relations
     *
     * @param array $only Relations to be resolved. If empty, resolve all relations
     *
     * @return array Returns relations, provided by the $only argument, as an associative array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function resolveRelations(array $only = []): array
    {
        $apiClient = app()->make(SwApiClient::class);

        $relationsToResolve = array_intersect($this->relations(), $only ?: $this->relations());

        foreach ($relationsToResolve as $prop) {
            if ($data = $this->data[$prop] ?? []) {
                $this->resolvedRelations[$prop] ??= $this->resolveProperty(
                    $prop,
                    (array)$data,
                    $apiClient
                );
            }
        }

        if ($only) {
            return array_intersect_key(
                $this->resolvedRelations,
                array_combine($only, array_fill(0, count($only), '-'))
            );
        }

        return $this->resolvedRelations;
    }

    /**
     * Returns model data along with (if any) resolved properties
     */
    public function toArray(): array
    {
        return array_merge($this->data, $this->resolvedRelations);
    }

    /**
     * Get read only model properties
     */
    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            throw new \InvalidArgumentException("Property `$name` does not exist on the class!");
        }

        return $this->data[$name];
    }

    /**
     * Get all model relations
     *
     * @return array|string[]
     */
    protected function relations(): array
    {
        if (property_exists($this, 'relations')) {
            return array_merge($this->baseRelations, $this->relations);
        }

        return $this->baseRelations;
    }

    /**
     * Resolve property type via api call
     */
    protected function resolveProperty(string $type, array $data, SwApiClient $apiClient): array
    {
        $links = [];

        foreach ($data as $link) {
            $result = $apiClient->get($link);

            if ($type === 'homeworld') {
                return $result;
            }

            array_push($links, $result);
        }

        return $links;
    }

    /**
     * Exclude keys, convert certain values to type
     */
    protected function prepareData(array $data): array
    {
        $keysToExclude = ['url'];

        $data = array_diff_key(
            $data,
            array_fill_keys($keysToExclude, '-')
        );

        if ($data['created'] ?? false) {
            $data['created'] = Carbon::make($data['created']);
        }
        if ($data['edited'] ?? false) {
            $data['edited'] = Carbon::make($data['edited']);
        }

        return $data;
    }
}
