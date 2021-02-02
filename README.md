# Installation

After cloning, 

`git clone git@github.com:tinkermanpro/swapi-demo.git`

run

```bash
composer install
npm install
npm run dev # or prod
```


## First task
Along with instructions is contained in task1.php inside this directory

# SWapi demo

## Given task

Using the Star Wars API: http://swapi.dev,
extend it with new endpoints and add in a form of some API client for below:

- Show all films where involved a given character acts
- Show all planets created after 12/04/2014
- Show people starships which have above 84000 passengers in total
- Showing all information of a Films model in as endpoint and then on FRONTEND display it in table using HTML/CSS 
  and some JS component (jQuery, VueJS, React or Angular). Be free to use some cool 3rd party library for Table component.

EXECUTION:
You are free to use some 3rd party packages and be creative on your own.

Also, keep in mind these points your application to have:
- Do some API docs or POSTMAN JSON collection share
- Make it (the code) look pretty: extensible, readable …
- Make sure your API Client has filtering and search options included as in API Docs stated

BONUS POINTS:
Want to impress us a bit more? Here are some tips to do so:
- Dockerize it
- Make some unit tests by TDD Framework of your choice


## Task solution
For the purpose of this task, no authentication/authorization has been implemented.

The following application extends the http://swapi.dev with a few extra endpoints

### ENDPOINTS

#### Films endpoint
```php
/** Returns all films. */
/api/films

/** 
 * Returns films filterable by character string.
 * Throws an validation error if character name not specific enough. 
 */
/api/films?filter[character]=luke skywalker

/** 
 * Returns the film resource with ID with resolved relationships 
 */
/api/films/:id
```

#### Planets endpoint
```php
/** Returns all planets. */
/api/planets
/** Returns all planets filterable by date created. */
/api/planets?filter[created]=12/04/2014
```

#### Starships endpoint
```php
/** Returns all starships. */
/api/starships
/** Returns all starships filterable by minimal number of passengers. */
/api/starships?filter[passengers]=84000
```

### SwApiClient class

```php
// initialize
$client = new SwApiClient(app()->make(\Illuminate\Http\Client\Factory::class)); 

// set endpoint with (optional) query parameters
$client->endpoint('planets', ['search' => 'Dathomir']);

// or use some of the aliases (which accept query params), same as above method

$client->planets();
// films alias accepts
$client->films();
$client->starships();
$client->people();

// Filter and search parameters for endpoint
$client ->searchFor('a') ->filterPage(3)->endpoint('planets')->getResults();
```

All methods are chainable and have to end with one of the following client methods.
- each method receives optional url string as a parameter.
- if the url parameter is provided, the client directly queries that url instead of building the endpoint.

```php
get(string $fullUrl = null) // returns results with metadata
getResults(string $fullUrl = null) // returns results only (without metadata)
getResponse(string $fullUrl = null) // returns response object
```

The following method is used to get all the resources from a paginated endpoint,
looping through each paged url.
Callback can be provided to filter each paged result.
```php
getAllResults(callable $callback = null)

// for example, get all the starships from API
$client->starships()->getAllResults();
```

### API endpoint example requests

Example request

``GET https://swapi.test/api/films``

Example response:

```json
{
    "current_page": 1,
    "data": [
        {
            "title": "A New Hope",
            "episode_id": 4,
            "opening_crawl": "It is a period of civil war.\r\nRebel spaceships, striking\r\nfrom a hidden base, have won\r\ntheir first victory against\r\nthe evil Galactic Empire.\r\n\r\nDuring the battle, Rebel\r\nspies managed to steal secret\r\nplans to the Empire's\r\nultimate weapon, the DEATH\r\nSTAR, an armored space\r\nstation with enough power\r\nto destroy an entire planet.\r\n\r\nPursued by the Empire's\r\nsinister agents, Princess\r\nLeia races home aboard her\r\nstarship, custodian of the\r\nstolen plans that can save her\r\npeople and restore\r\nfreedom to the galaxy....",
            "director": "George Lucas",
            "producer": "Gary Kurtz, Rick McCallum",
            "release_date": "1977-05-25",
            "characters": [
                "http:\/\/swapi.dev\/api\/people\/1\/",
                "http:\/\/swapi.dev\/api\/people\/2\/",
                "http:\/\/swapi.dev\/api\/people\/3\/",
                "http:\/\/swapi.dev\/api\/people\/4\/",
                "http:\/\/swapi.dev\/api\/people\/5\/",
                "http:\/\/swapi.dev\/api\/people\/6\/",
                "http:\/\/swapi.dev\/api\/people\/7\/",
                "http:\/\/swapi.dev\/api\/people\/8\/",
                "http:\/\/swapi.dev\/api\/people\/9\/",
                "http:\/\/swapi.dev\/api\/people\/10\/",
                "http:\/\/swapi.dev\/api\/people\/12\/",
                "http:\/\/swapi.dev\/api\/people\/13\/",
                "http:\/\/swapi.dev\/api\/people\/14\/",
                "http:\/\/swapi.dev\/api\/people\/15\/",
                "http:\/\/swapi.dev\/api\/people\/16\/",
                "http:\/\/swapi.dev\/api\/people\/18\/",
                "http:\/\/swapi.dev\/api\/people\/19\/",
                "http:\/\/swapi.dev\/api\/people\/81\/"
            ],
            "planets": [
                "http:\/\/swapi.dev\/api\/planets\/1\/",
                "http:\/\/swapi.dev\/api\/planets\/2\/",
                "http:\/\/swapi.dev\/api\/planets\/3\/"
            ],
            "starships": [
                "http:\/\/swapi.dev\/api\/starships\/2\/",
                "http:\/\/swapi.dev\/api\/starships\/3\/",
                "http:\/\/swapi.dev\/api\/starships\/5\/",
                "http:\/\/swapi.dev\/api\/starships\/9\/",
                "http:\/\/swapi.dev\/api\/starships\/10\/",
                "http:\/\/swapi.dev\/api\/starships\/11\/",
                "http:\/\/swapi.dev\/api\/starships\/12\/",
                "http:\/\/swapi.dev\/api\/starships\/13\/"
            ],
            "vehicles": [
                "http:\/\/swapi.dev\/api\/vehicles\/4\/",
                "http:\/\/swapi.dev\/api\/vehicles\/6\/",
                "http:\/\/swapi.dev\/api\/vehicles\/7\/",
                "http:\/\/swapi.dev\/api\/vehicles\/8\/"
            ],
            "species": [
                "http:\/\/swapi.dev\/api\/species\/1\/",
                "http:\/\/swapi.dev\/api\/species\/2\/",
                "http:\/\/swapi.dev\/api\/species\/3\/",
                "http:\/\/swapi.dev\/api\/species\/4\/",
                "http:\/\/swapi.dev\/api\/species\/5\/"
            ],
            "created": "2014-12-10T14:23:31.880000Z",
            "edited": "2014-12-20T19:49:45.256000Z",
            "url": "http:\/\/swapi.dev\/api\/films\/1\/"
        },
        ...
    ],
    "first_page_url": "http:\/\/swapi.test\/api\/films?page=1",
    "from": 1,
    "last_page": 2,
    "last_page_url": "http:\/\/swapi.test\/api\/films?page=2",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http:\/\/swapi.test\/api\/films?page=1",
            "label": 1,
            "active": true
        },
        {
            "url": "http:\/\/swapi.test\/api\/films?page=2",
            "label": 2,
            "active": false
        },
        {
            "url": "http:\/\/swapi.test\/api\/films?page=2",
            "label": "Next &raquo;",
            "active": false
        }
    ],
    "next_page_url": "http:\/\/swapi.test\/api\/films?page=2",
    "path": "http:\/\/swapi.test\/api\/films",
    "per_page": 3,
    "prev_page_url": null,
    "to": 3,
    "total": 6
}
```

Example request (for a specific ID, resolves first level relationships)

``GET https://swapi.test/api/film/1``

Example response:

```json
{
    "title": "A New Hope",
    "episode_id": 4,
    "opening_crawl": "It is a period of civil war.\r\nRebel spaceships, striking\r\nfrom a hidden base, have won\r\ntheir first victory against\r\nthe evil Galactic Empire.\r\n\r\nDuring the battle, Rebel\r\nspies managed to steal secret\r\nplans to the Empire's\r\nultimate weapon, the DEATH\r\nSTAR, an armored space\r\nstation with enough power\r\nto destroy an entire planet.\r\n\r\nPursued by the Empire's\r\nsinister agents, Princess\r\nLeia races home aboard her\r\nstarship, custodian of the\r\nstolen plans that can save her\r\npeople and restore\r\nfreedom to the galaxy....",
    "director": "George Lucas",
    "producer": "Gary Kurtz, Rick McCallum",
    "release_date": "1977-05-25",
    "characters": [
        {
            "name": "Luke Skywalker",
            "height": "172",
            "mass": "77",
            "hair_color": "blond",
            "skin_color": "fair",
            "eye_color": "blue",
            "birth_year": "19BBY",
            "gender": "male",
            "homeworld": "http:\/\/swapi.dev\/api\/planets\/1\/",
            "films": [
                "http:\/\/swapi.dev\/api\/films\/1\/",
                "http:\/\/swapi.dev\/api\/films\/2\/",
                "http:\/\/swapi.dev\/api\/films\/3\/",
                "http:\/\/swapi.dev\/api\/films\/6\/"
            ],
            "species": [],
            "vehicles": [
                "http:\/\/swapi.dev\/api\/vehicles\/14\/",
                "http:\/\/swapi.dev\/api\/vehicles\/30\/"
            ],
            "starships": [
                "http:\/\/swapi.dev\/api\/starships\/12\/",
                "http:\/\/swapi.dev\/api\/starships\/22\/"
            ],
            "created": "2014-12-09T13:50:51.644000Z",
            "edited": "2014-12-20T21:17:56.891000Z",
            "url": "http:\/\/swapi.dev\/api\/people\/1\/"
        },
        ...
    ],
    "planets": [
        {
            "name": "Tatooine",
            "rotation_period": "23",
            "orbital_period": "304",
            "diameter": "10465",
            "climate": "arid",
            "gravity": "1 standard",
            "terrain": "desert",
            "surface_water": "1",
            "population": "200000",
            "residents": [
                "http:\/\/swapi.dev\/api\/people\/1\/",
                "http:\/\/swapi.dev\/api\/people\/2\/",
                "http:\/\/swapi.dev\/api\/people\/4\/",
                "http:\/\/swapi.dev\/api\/people\/6\/",
                "http:\/\/swapi.dev\/api\/people\/7\/",
                "http:\/\/swapi.dev\/api\/people\/8\/",
                "http:\/\/swapi.dev\/api\/people\/9\/",
                "http:\/\/swapi.dev\/api\/people\/11\/",
                "http:\/\/swapi.dev\/api\/people\/43\/",
                "http:\/\/swapi.dev\/api\/people\/62\/"
            ],
            "films": [
                "http:\/\/swapi.dev\/api\/films\/1\/",
                "http:\/\/swapi.dev\/api\/films\/3\/",
                "http:\/\/swapi.dev\/api\/films\/4\/",
                "http:\/\/swapi.dev\/api\/films\/5\/",
                "http:\/\/swapi.dev\/api\/films\/6\/"
            ],
            "created": "2014-12-09T13:50:49.641000Z",
            "edited": "2014-12-20T20:58:18.411000Z",
            "url": "http:\/\/swapi.dev\/api\/planets\/1\/"
        },
        ...
    ],
    "vehicles": [
        {
            "name": "Sand Crawler",
            "model": "Digger Crawler",
            "manufacturer": "Corellia Mining Corporation",
            "cost_in_credits": "150000",
            "length": "36.8 ",
            "max_atmosphering_speed": "30",
            "crew": "46",
            "passengers": "30",
            "cargo_capacity": "50000",
            "consumables": "2 months",
            "vehicle_class": "wheeled",
            "pilots": [],
            "films": [
                "http:\/\/swapi.dev\/api\/films\/1\/",
                "http:\/\/swapi.dev\/api\/films\/5\/"
            ],
            "created": "2014-12-10T15:36:25.724000Z",
            "edited": "2014-12-20T21:30:21.661000Z",
            "url": "http:\/\/swapi.dev\/api\/vehicles\/4\/"
        },
        ...
    ],
    "species": [
        {
            "name": "Human",
            "classification": "mammal",
            "designation": "sentient",
            "average_height": "180",
            "skin_colors": "caucasian, black, asian, hispanic",
            "hair_colors": "blonde, brown, black, red",
            "eye_colors": "brown, blue, green, hazel, grey, amber",
            "average_lifespan": "120",
            "homeworld": "http:\/\/swapi.dev\/api\/planets\/9\/",
            "language": "Galactic Basic",
            "people": [
                "http:\/\/swapi.dev\/api\/people\/66\/",
                "http:\/\/swapi.dev\/api\/people\/67\/",
                "http:\/\/swapi.dev\/api\/people\/68\/",
                "http:\/\/swapi.dev\/api\/people\/74\/"
            ],
            "films": [
                "http:\/\/swapi.dev\/api\/films\/1\/",
                "http:\/\/swapi.dev\/api\/films\/2\/",
                "http:\/\/swapi.dev\/api\/films\/3\/",
                "http:\/\/swapi.dev\/api\/films\/4\/",
                "http:\/\/swapi.dev\/api\/films\/5\/",
                "http:\/\/swapi.dev\/api\/films\/6\/"
            ],
            "created": "2014-12-10T13:52:11.567000Z",
            "edited": "2014-12-20T21:36:42.136000Z",
            "url": "http:\/\/swapi.dev\/api\/species\/1\/"
        },
        ...
    ],
    "created": "2014-12-10T14:23:31.880000Z",
    "edited": "2014-12-20T19:49:45.256000Z"
}
```



Example request

``GET http://swapi.test/api/films?filter[character]=anakin%20skywalker``

Example response:

```json
{
    "current_page": 1,
    "data": [
        {
            "title": "The Phantom Menace",
            "episode_id": 1,
            "opening_crawl": "Turmoil has engulfed the\r\nGalactic Republic. The taxation\r\nof trade routes to outlying star\r\nsystems is in dispute.\r\n\r\nHoping to resolve the matter\r\nwith a blockade of deadly\r\nbattleships, the greedy Trade\r\nFederation has stopped all\r\nshipping to the small planet\r\nof Naboo.\r\n\r\nWhile the Congress of the\r\nRepublic endlessly debates\r\nthis alarming chain of events,\r\nthe Supreme Chancellor has\r\nsecretly dispatched two Jedi\r\nKnights, the guardians of\r\npeace and justice in the\r\ngalaxy, to settle the conflict....",
            "director": "George Lucas",
            "producer": "Rick McCallum",
            "release_date": "1999-05-19",
            "characters": [
                "http:\/\/swapi.dev\/api\/people\/2\/",
                "http:\/\/swapi.dev\/api\/people\/3\/",
                "http:\/\/swapi.dev\/api\/people\/10\/",
                "http:\/\/swapi.dev\/api\/people\/11\/",
                "http:\/\/swapi.dev\/api\/people\/16\/",
                "http:\/\/swapi.dev\/api\/people\/20\/",
                "http:\/\/swapi.dev\/api\/people\/21\/",
                "http:\/\/swapi.dev\/api\/people\/32\/",
                "http:\/\/swapi.dev\/api\/people\/33\/",
                "http:\/\/swapi.dev\/api\/people\/34\/",
                "http:\/\/swapi.dev\/api\/people\/35\/",
                "http:\/\/swapi.dev\/api\/people\/36\/",
                "http:\/\/swapi.dev\/api\/people\/37\/",
                "http:\/\/swapi.dev\/api\/people\/38\/",
                "http:\/\/swapi.dev\/api\/people\/39\/",
                "http:\/\/swapi.dev\/api\/people\/40\/",
                "http:\/\/swapi.dev\/api\/people\/41\/",
                "http:\/\/swapi.dev\/api\/people\/42\/",
                "http:\/\/swapi.dev\/api\/people\/43\/",
                "http:\/\/swapi.dev\/api\/people\/44\/",
                "http:\/\/swapi.dev\/api\/people\/46\/",
                "http:\/\/swapi.dev\/api\/people\/47\/",
                "http:\/\/swapi.dev\/api\/people\/48\/",
                "http:\/\/swapi.dev\/api\/people\/49\/",
                "http:\/\/swapi.dev\/api\/people\/50\/",
                "http:\/\/swapi.dev\/api\/people\/51\/",
                "http:\/\/swapi.dev\/api\/people\/52\/",
                "http:\/\/swapi.dev\/api\/people\/53\/",
                "http:\/\/swapi.dev\/api\/people\/54\/",
                "http:\/\/swapi.dev\/api\/people\/55\/",
                "http:\/\/swapi.dev\/api\/people\/56\/",
                "http:\/\/swapi.dev\/api\/people\/57\/",
                "http:\/\/swapi.dev\/api\/people\/58\/",
                "http:\/\/swapi.dev\/api\/people\/59\/"
            ],
            "planets": [
                "http:\/\/swapi.dev\/api\/planets\/1\/",
                "http:\/\/swapi.dev\/api\/planets\/8\/",
                "http:\/\/swapi.dev\/api\/planets\/9\/"
            ],
            "starships": [
                "http:\/\/swapi.dev\/api\/starships\/31\/",
                "http:\/\/swapi.dev\/api\/starships\/32\/",
                "http:\/\/swapi.dev\/api\/starships\/39\/",
                "http:\/\/swapi.dev\/api\/starships\/40\/",
                "http:\/\/swapi.dev\/api\/starships\/41\/"
            ],
            "vehicles": [
                "http:\/\/swapi.dev\/api\/vehicles\/33\/",
                "http:\/\/swapi.dev\/api\/vehicles\/34\/",
                "http:\/\/swapi.dev\/api\/vehicles\/35\/",
                "http:\/\/swapi.dev\/api\/vehicles\/36\/",
                "http:\/\/swapi.dev\/api\/vehicles\/37\/",
                "http:\/\/swapi.dev\/api\/vehicles\/38\/",
                "http:\/\/swapi.dev\/api\/vehicles\/42\/"
            ],
            "species": [
                "http:\/\/swapi.dev\/api\/species\/1\/",
                "http:\/\/swapi.dev\/api\/species\/2\/",
                "http:\/\/swapi.dev\/api\/species\/6\/",
                "http:\/\/swapi.dev\/api\/species\/11\/",
                "http:\/\/swapi.dev\/api\/species\/12\/",
                "http:\/\/swapi.dev\/api\/species\/13\/",
                "http:\/\/swapi.dev\/api\/species\/14\/",
                "http:\/\/swapi.dev\/api\/species\/15\/",
                "http:\/\/swapi.dev\/api\/species\/16\/",
                "http:\/\/swapi.dev\/api\/species\/17\/",
                "http:\/\/swapi.dev\/api\/species\/18\/",
                "http:\/\/swapi.dev\/api\/species\/19\/",
                "http:\/\/swapi.dev\/api\/species\/20\/",
                "http:\/\/swapi.dev\/api\/species\/21\/",
                "http:\/\/swapi.dev\/api\/species\/22\/",
                "http:\/\/swapi.dev\/api\/species\/23\/",
                "http:\/\/swapi.dev\/api\/species\/24\/",
                "http:\/\/swapi.dev\/api\/species\/25\/",
                "http:\/\/swapi.dev\/api\/species\/26\/",
                "http:\/\/swapi.dev\/api\/species\/27\/"
            ],
            "created": "2014-12-19T16:52:55.740000Z",
            "edited": "2014-12-20T10:54:07.216000Z"
        },
        ...
    ],
    "first_page_url": "http:\/\/swapi.test\/api\/films?filter%5Bcharacter%5D=anakin%20skywalker&page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/swapi.test\/api\/films?filter%5Bcharacter%5D=anakin%20skywalker&page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http:\/\/swapi.test\/api\/films?filter%5Bcharacter%5D=anakin%20skywalker&page=1",
            "label": 1,
            "active": true
        },
        {
            "url": null,
            "label": "Next &raquo;",
            "active": false
        }
    ],
    "next_page_url": null,
    "path": "http:\/\/swapi.test\/api\/films",
    "per_page": 3,
    "prev_page_url": null,
    "to": 3,
    "total": 3
}
```
