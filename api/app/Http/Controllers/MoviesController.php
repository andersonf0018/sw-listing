<?php

namespace App\Http\Controllers;

use App\Traits\SwapiResourceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
class MoviesController extends Controller
{
    use SwapiResourceTrait;

    protected $baseUrl = 'https://swapi.py4e.com/api/films/';
    protected $cacheTime = 1440;
    public function index(Request $request)
    {
        try {
            $search = $request->query('search', '');
            $page = $request->query('page', 1);
            $cacheKey = 'movies.index.' . $search . '.' . $page;

            if (Cache::has($cacheKey)) {
                return response()->json(Cache::get($cacheKey));
            }

            $response = Http::timeout(30)->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl, ['page' => $page, 'search' => $search]);
    
            if ($response->failed()) {
                Log::error("Failed to fetch movies data from SWAPI: ", ['status' => $response->status(), 'body' => $response->body()]);
                return response()->json(['error' => 'Failed to fetch movies data from SWAPI'], 500);
            }
    
            $data = $response->json();
    
            if (isset($data['results']) && is_array($data['results'])) {
                $data['results'] = array_map(function ($film) {
                    return $this->populateRelatedFilmData($film);
                }, $data['results']);

                Cache::put($cacheKey, $data, now()->addMinutes($this->cacheTime));
            } else {
                Log::warning("SWAPI response does not contain a valid 'results' array.");
                $data['results'] = [];
            }
    
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error("Error fetching movies data: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch movies data from SWAPI'], 500);
        }
    }

    public function show($id)
    {
        try {
            $cacheKey = 'movies.show.' . $id;

            if (Cache::has($cacheKey)) {
                return response()->json(Cache::get($cacheKey));
            }

            $response = Http::timeout(30)->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->get("{$this->baseUrl}{$id}/");

            if ($response->failed()) {
                Log::error("Failed to fetch movie data from SWAPI: ", ['status' => $response->status(), 'body' => $response->body()]);
                return response()->json(['error' => 'Failed to fetch movie data from SWAPI'], 500);
            }
    
            $film = $response->json();

            Cache::put($cacheKey, $film, now()->addMinutes($this->cacheTime));
    
            return response()->json($this->populateRelatedFilmData($film));
        } catch (\Exception $e) {
            Log::error("Error fetching movie data: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch movie data from SWAPI'], 500);
        }
    }
}

