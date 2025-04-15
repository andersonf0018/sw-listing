<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
class StatisticsController extends Controller
{
    /**
     * Get overview of API statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $global = Cache::get('stats.global', [
                'total_requests' => 0,
                'total_searches' => 0,
                'total_detail_views' => 0,
                'avg_response_time' => 0,
                'unique_search_queries' => 0,
                'unique_entities_viewed' => 0,
                'last_calculated_at' => now()->toIso8601String(),
            ]);
            
            $topSearches = Cache::get('stats.searches.top', []);
            if (count($topSearches) > 0 && $global['total_searches'] > 0) {
                $topSearches = collect($topSearches)->map(function ($item) use ($global) {
                    $item->percentage = round(($item->count / $global['total_searches']) * 100, 2);
                    return $item;
                });
            }
            
            $topViewed = Cache::get('stats.detail_views.top', []);
            
            $responseTimes = Cache::get('stats.response_times.by_event_type', []);
            
            $trafficByHour = Cache::get('stats.traffic.by_hour', []);
            
            return response()->json([
                'global' => $global,
                'top_searches' => $topSearches,
                'top_viewed_entities' => $topViewed,
                'response_times' => $responseTimes,
                'traffic_by_hour' => $trafficByHour,
                'calculation_frequency' => 'Every 5 minutes',
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching statistics: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch statistics'], 500);
        }
    }
    
    /**
     * Get detailed search statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function searches(): JsonResponse
    {
        try {
            return response()->json([
                'top_searches' => Cache::get('stats.searches.top', []),
                'searches_by_type' => Cache::get('stats.searches.by_type', []),
                'zero_result_searches' => Cache::get('stats.searches.zero_results', []),
                'last_calculated_at' => Cache::get('stats.global.last_calculated_at', now()->toIso8601String()),
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching searches statistics: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch searches statistics'], 500);
        }
    }
    
    /**
     * Get detailed view statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detailViews(): JsonResponse
    {
        try {
            return response()->json([
                'top_viewed' => Cache::get('stats.detail_views.top', []),
                'views_by_type' => Cache::get('stats.detail_views.by_type', []),
                'last_calculated_at' => Cache::get('stats.global.last_calculated_at', now()->toIso8601String()),
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching detail views statistics: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch detail views statistics'], 500);
        }
    }
    
    /**
     * Get performance statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function performance(): JsonResponse
    {
        try {
            return response()->json([
                'by_event_type' => Cache::get('stats.response_times.by_event_type', []),
                'by_entity_type' => Cache::get('stats.response_times.by_entity_type', []),
                'slowest_endpoints' => Cache::get('stats.response_times.slowest_endpoints', []),
                'last_calculated_at' => Cache::get('stats.global.last_calculated_at', now()->toIso8601String()),
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching performance statistics: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch performance statistics'], 500);
        }
    }
    
    /**
     * Get traffic pattern statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function traffic(): JsonResponse
    {
        try {
            return response()->json([
                'by_hour' => Cache::get('stats.traffic.by_hour', []),
                'by_day' => Cache::get('stats.traffic.by_day', []),
                'by_event_type' => Cache::get('stats.traffic.by_event_type', []),
                'last_calculated_at' => Cache::get('stats.global.last_calculated_at', now()->toIso8601String()),
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching traffic statistics: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch traffic statistics'], 500);
        }
    }
}
