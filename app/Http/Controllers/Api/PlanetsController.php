<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\SwApi\SwApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanetsController extends Controller
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
            ['filter.created' => 'date'],
            [],
            ['filter.created' => 'Created filter'],
        );

        if ($date = $request->input('filter.created')) {
            $collection = $this->swApi->getPlanetsCreatedAfter(Carbon::make($date));
        } else {
            $collection = $this->swApi->getPlanets();
        }

        return $collection->paginate(3, $request->page);

//        return new ResourceCollection(
//
//        );
    }
}
