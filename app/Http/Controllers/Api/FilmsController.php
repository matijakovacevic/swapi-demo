<?php

namespace App\Http\Controllers\Api;

use App\SwApi\Collection;
use App\SwApi\Models\Film;
use Illuminate\Http\Request;
use App\SwApi\SwApiInterface;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FilmsController extends Controller
{
    private SwApiInterface $swApi;

    public function __construct(SwApiInterface $swApi)
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
            ['filter.character' => 'string|min:2'],
            [],
            ['filter.character' => 'Character filter'],
        );

        if ($name = $request->input('filter.character')) {

            try {

                $character = $this->swApi->getCharacterByName($name);

            } catch (\LogicException $e) {
                throw ValidationException::withMessages([
                    'filter.character' => $e->getMessage(),
                ]);
            }

            $films = $this->swApi->getFilmsFromCharacter($character);

            $collection = Collection::make($films)->mapInto(Film::class);
        } else {
            $collection = $this->swApi->getFilms();
        }

        return response($collection->paginate(3, $request->page));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $film = $this->swApi->getFilmById($id);
        $film->resolveRelations();

        return new JsonResponse($film);
    }
}
