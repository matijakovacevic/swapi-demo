<?php

namespace App\SwApi;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Custom collection class containing paginator logic for non-eloquent collections
 */
class Collection extends \Illuminate\Support\Collection
{
    public function paginate(int $perPage, ?int $currentPage): LengthAwarePaginator
    {
        $currentPage = $currentPage ?: 1;
        $offset = ($currentPage-1) * $perPage;

        $count = $this->count();

        $urlGenerator = url();

        $paginator = new LengthAwarePaginator(
            $this->splice($offset, $perPage),
            $count,
            $perPage,
            $currentPage,
            [
                'path' => $urlGenerator->current(),
            ]
        );

        return $paginator->withQueryString();
    }
}
