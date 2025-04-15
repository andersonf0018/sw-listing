<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait SwapiResourceTrait
{
    /**
     * Fetch and populate related resources for a SWAPI entity
     * 
     * @param array $entity The entity to populate
     * @return array The entity with populated related data
     */
    protected function populateRelatedPersonData(array $entity): array
    {
        if (!empty($entity['films']) && is_array($entity['films'])) {
            $entity["films_data"] = [];

            foreach ($entity['films'] as $url) {
                $relatedData = $this->fetchResource($url);

                if ($relatedData) {
                    $entity["films_data"][] = [
                        'id' => $relatedData['id'],
                        'title' => $relatedData['title'] ?? null
                    ];
                }
            }
        }

        return $entity;
    }

    protected function populateRelatedFilmData(array $entity): array
    {
        if (!empty($entity['characters']) && is_array($entity['characters'])) {
            $entity["characters_data"] = [];

            foreach ($entity['characters'] as $url) {
                $relatedData = $this->fetchResource($url);

                if ($relatedData) {
                    $entity["characters_data"][] = [
                        'id' => $relatedData['id'],
                        'name' => $relatedData['name'] ?? null
                    ];
                } else {
                    Log::warning("Related data for character could not be fetched.");
                }
            }
        }

        $urlParts = explode('/', rtrim($entity['url'], '/'));
        $entity["id"] = end($urlParts);

        return $entity;
    }


    /**
     * Fetch a resource from a URL with caching
     * 
     * @param string $url The URL to fetch
     * @return array|null The resource data or null on failure
     */
    protected function fetchResource(string $url): ?array
    {
        $urlParts = explode('/', rtrim($url, '/'));
        $id = end($urlParts);
        $type = $urlParts[count($urlParts) - 2];

        $cacheKey = "swapi_{$type}_{$id}";

        return Cache::remember($cacheKey, 24 * 60 * 60, function () use ($url) {
            try {
                $response = Http::get($url);

                if ($response->successful()) {
                    $data = $response->json();

                    $urlParts = explode('/', rtrim($data['url'], '/'));
                    $data['id'] = end($urlParts);

                    return $data;
                }
            } catch (\Exception $e) {
                Log::error("Failed to fetch SWAPI resource: {$e->getMessage()}");
            }

            return null;
        });
    }
}
