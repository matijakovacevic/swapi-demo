<?php

namespace App\SwApi;

interface SwApiClientInterface
{
    public function getAllResults(callable $callback = null): Collection;
}
