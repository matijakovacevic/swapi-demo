<?php

namespace App\Http\Controllers\Api;

use App\SwApi\SwApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StarshipsController extends Controller
{
    private SwApi $swApi;

    public function __construct(SwApi $swApi)
    {
        $this->swApi = $swApi;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate(
            ['filter.passengers' => 'string|min:2'],
            [],
            ['filter.passengers' => 'Passengers number filter'],
        );

        if ($count = (int)$request->input('filter.passengers')) {
            $collection = $this->swApi->getStarshipsWithMinimalPassengersCount($count);
        } else {
            $collection = $this->swApi->getStarships();
        }

        return $collection->paginate(3, $request->page);
    }
}
