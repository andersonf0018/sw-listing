<?php

namespace App\Http\Controllers;

use App\Traits\SwapiResourceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PeopleController extends Controller
{
    use SwapiResourceTrait;

    protected $baseUrl = 'https://swapi.py4e.com/api/people/';
    protected $cacheTime = 1440;

    public function index(Request $request)
    {
        try {
            $search = $request->query('search', '');
            $page = $request->query('page', 1);
            $cacheKey = 'people.index.' . $search . '.' . $page;

            if (Cache::has($cacheKey)) {
                return response()->json(Cache::get($cacheKey));
            }

            $response = Http::timeout(30)->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl, ['page' => $page, 'search' => $search]);

            if ($response->failed()) {
                Log::error("Failed to fetch people data from SWAPI: ", ['status' => $response->status(), 'body' => $response->body()]);
                return response()->json(['error' => 'Failed to fetch people data from SWAPI'], 500);
            }

            $data = $response->json();

            if (isset($data['results']) && is_array($data['results'])) {
                $data['results'] = array_map(function ($person) {
                    $urlParts = explode('/', rtrim($person['url'], '/'));
                    $person['id'] = end($urlParts);
                    return $this->populateRelatedPersonData($person);
                }, $data['results']);

                Cache::put($cacheKey, $data, now()->addMinutes($this->cacheTime));
            } else {
                Log::warning("SWAPI response does not contain a valid 'results' array.");
                $data['results'] = [];
            }

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error("Error fetching people data: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch people data from SWAPI'], 500);
        }
    }

    public function show($id)
    {
        try {
            $cacheKey = 'people.show.' . $id;

            if (Cache::has($cacheKey)) {
                return response()->json(Cache::get($cacheKey));
            }

            $response = Http::timeout(30)->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->get("{$this->baseUrl}{$id}/");

            if ($response->failed()) {
                Log::error("Failed to fetch person data from SWAPI: ", ['status' => $response->status(), 'body' => $response->body()]);
                return response()->json(['error' => 'Failed to fetch person data from SWAPI'], 500);
            }

            $person = $response->json();
            $person['id'] = $id;

            Cache::put($cacheKey, $person, now()->addMinutes($this->cacheTime));

            return response()->json($this->populateRelatedPersonData($person));
        } catch (\Exception $e) {
            Log::error("Error fetching person data: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch person data from SWAPI'], 500);
        }
    }
}
