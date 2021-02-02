<?php

namespace App\SwApi;

use ReflectionFunction;
use Illuminate\Cache\CacheManager;
use Illuminate\Http\Client\Factory;

class SwApiCacheDecorator extends SwApiClient
{
    private CacheManager $cache;
    private int $cacheSeconds;

    public function __construct(CacheManager $cache, Factory $http)
    {
        $this->cache = $cache;
        $this->cacheSeconds = 60*5;

        parent::__construct($http);
    }

    public function getAllResults(callable $callback = null): Collection
    {
        $key = $this->endpoint;

        if ($callback) {
            $fn = new ReflectionFunction($callback);

            $key = md5($fn->getFileName() . $fn->getStartLine() . $fn->getEndLine());
        }

        return $this->cache->remember($key, $this->cacheSeconds, function () use ($callback) {
            return parent::getAllResults($callback);
        });
    }
}
