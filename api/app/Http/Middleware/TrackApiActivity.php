<?php

namespace App\Http\Middleware;

use App\Models\ApiActivity;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackApiActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        $response = $next($request);
        
        $responseTime = microtime(true) - $startTime;
        
        try {
            $eventData = $this->determineEventData($request, $response, $responseTime);
            
            if ($eventData) {
                ApiActivity::create($eventData);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error tracking API activity: ' . $e->getMessage());
        }
        
        return $response;
    }
    
    /**
     * Determine the event type and gather relevant data.
     */
    private function determineEventData(Request $request, Response $response, float $responseTime): ?array
    {
        $data = [
            'response_time' => $responseTime,
            'user_id' => Auth::id() ?? null,
            'metadata' => [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status_code' => $response->getStatusCode(),
            ],
        ];
        
        if ($request->has('search')) {
            return array_merge($data, [
                'event_type' => 'search',
                'query' => $request->get('search'),
                'entity_type' => $request->get('type'),
            ]);
        }
        
        if (preg_match('/(api\/)?(people|movies)\/(\d+)/', $request->path(), $matches)) {
            $entityType = $this->normalizeEntityType($matches[2]);
            $entityId = $matches[3];
            
            $entityName = null;
            if ($response->getStatusCode() === 200) {
                $content = json_decode($response->getContent(), true);
                $entityName = $content['name'] ?? $content['title'] ?? null;
            }
            
            return array_merge($data, [
                'event_type' => 'detail_view',
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'entity_name' => $entityName,
            ]);
        } 
        
        return array_merge($data, [
            'event_type' => 'api_call',
            'query' => $request->path(),
            'metadata' => array_merge($data['metadata'], [
                'method' => $request->method(),
                'params' => $request->all(),
            ]),
        ]);
    }
    
    /**
     * Normalize entity type from API endpoint to database category
     */
    private function normalizeEntityType(string $entityType): string
    {
        return [
            'people' => 'character',
            'movies' => 'movie',
        ][$entityType] ?? $entityType;
    }
} 