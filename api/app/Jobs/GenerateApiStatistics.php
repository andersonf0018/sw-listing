<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GenerateApiStatistics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    private $statisticTypes = [
        'searches',
        'detail_views',
        'response_times',
        'traffic_patterns',
    ];

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Starting API statistics generation');
            
            foreach ($this->statisticTypes as $type) {
                $method = 'calculate' . str_replace('_', '', ucwords($type, '_'));
                if (method_exists($this, $method)) {
                    Log::info("Calculating $type statistics");
                    $this->$method();
                }
            }
            
            $this->calculateGlobalStats();
            
            Log::info('Completed API statistics generation');
        } catch (\Exception $e) {
            Log::error('Error generating API statistics: ' . $e->getMessage());
        }
    }
    
    /**
     * Calculate search-related statistics
     */
    private function calculateSearches(): void
    {
        $topSearches = DB::table('api_activities')
            ->select('query', DB::raw('COUNT(*) as count'))
            ->where('event_type', 'search')
            ->whereNotNull('query')
            ->groupBy('query')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        $entityTypes = DB::table('api_activities')
            ->where('event_type', 'search')
            ->whereNotNull('entity_type')
            ->distinct()
            ->pluck('entity_type');
            
        $searchesByType = [];
        foreach ($entityTypes as $type) {
            $searchesByType[$type] = DB::table('api_activities')
                ->select('query', DB::raw('COUNT(*) as count'))
                ->where('event_type', 'search')
                ->where('entity_type', $type)
                ->groupBy('query')
                ->orderByDesc('count')
                ->limit(5)
                ->get();
        }
        
        $zeroResultSearches = DB::table('api_activities')
            ->select('query', DB::raw('COUNT(*) as count'))
            ->where('event_type', 'search')
            ->whereJsonContains('metadata->result_count', 0)
            ->groupBy('query')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
            
        Cache::put('stats.searches.top', $topSearches, now()->addMinutes(10));
        Cache::put('stats.searches.by_type', $searchesByType, now()->addMinutes(10));
        Cache::put('stats.searches.zero_results', $zeroResultSearches, now()->addMinutes(10));
    }
    
    /**
     * Calculate detail view statistics
     */
    private function calculateDetailViews(): void
    {
        $topViewed = DB::table('api_activities')
            ->select('entity_type', 'entity_id', 'entity_name', DB::raw('COUNT(*) as count'))
            ->where('event_type', 'detail_view')
            ->whereNotNull('entity_id')
            ->groupBy('entity_type', 'entity_id', 'entity_name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
            
        $entityTypes = DB::table('api_activities')
            ->where('event_type', 'detail_view')
            ->whereNotNull('entity_type')
            ->distinct()
            ->pluck('entity_type');
            
        $viewsByType = [];
        foreach ($entityTypes as $type) {
            $viewsByType[$type] = DB::table('api_activities')
                ->select('entity_id', 'entity_name', DB::raw('COUNT(*) as count'))
                ->where('event_type', 'detail_view')
                ->where('entity_type', $type)
                ->groupBy('entity_id', 'entity_name')
                ->orderByDesc('count')
                ->limit(5)
                ->get();
        }
        
        Cache::put('stats.detail_views.top', $topViewed, now()->addMinutes(10));
        Cache::put('stats.detail_views.by_type', $viewsByType, now()->addMinutes(10));
    }
    
    /**
     * Calculate response time statistics
     */
    private function calculateResponseTimes(): void
    {
        $avgByEventType = DB::table('api_activities')
            ->select('event_type', DB::raw('AVG(response_time) as avg_time'))
            ->whereNotNull('response_time')
            ->groupBy('event_type')
            ->get();
            
        $avgByEntityType = DB::table('api_activities')
            ->select('entity_type', DB::raw('AVG(response_time) as avg_time'))
            ->where('event_type', 'detail_view')
            ->whereNotNull('entity_type')
            ->whereNotNull('response_time')
            ->groupBy('entity_type')
            ->get();
            
        $slowestEndpoints = DB::table('api_activities')
            ->select('query', DB::raw('AVG(response_time) as avg_time'), DB::raw('COUNT(*) as count'))
            ->where('event_type', 'api_call')
            ->whereNotNull('response_time')
            ->groupBy('query')
            ->having('count', '>', 5) // Only include endpoints with sufficient data
            ->orderByDesc('avg_time')
            ->limit(10)
            ->get();
            
        Cache::put('stats.response_times.by_event_type', $avgByEventType, now()->addMinutes(10));
        Cache::put('stats.response_times.by_entity_type', $avgByEntityType, now()->addMinutes(10));
        Cache::put('stats.response_times.slowest_endpoints', $slowestEndpoints, now()->addMinutes(10));
    }
    
    /**
     * Calculate traffic pattern statistics
     */
    private function calculateTrafficPatterns(): void
    {
        $trafficByHour = DB::table('api_activities')
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
            
        $trafficByDay = DB::table('api_activities')
            ->select(DB::raw('DAYOFWEEK(created_at) as day'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('day')
            ->orderBy('day')
            ->get();
            
        $trafficByEventType = DB::table('api_activities')
            ->select('event_type', DB::raw('COUNT(*) as count'))
            ->groupBy('event_type')
            ->orderByDesc('count')
            ->get();
            
        Cache::put('stats.traffic.by_hour', $trafficByHour, now()->addMinutes(10));
        Cache::put('stats.traffic.by_day', $trafficByDay, now()->addMinutes(10));
        Cache::put('stats.traffic.by_event_type', $trafficByEventType, now()->addMinutes(10));
    }
    
    /**
     * Calculate global statistics
     */
    private function calculateGlobalStats(): void
    {
        $stats = [
            'total_requests' => DB::table('api_activities')->count(),
            'total_searches' => DB::table('api_activities')->where('event_type', 'search')->count(),
            'total_detail_views' => DB::table('api_activities')->where('event_type', 'detail_view')->count(),
            'avg_response_time' => DB::table('api_activities')->whereNotNull('response_time')->avg('response_time'),
            'unique_search_queries' => DB::table('api_activities')
                ->where('event_type', 'search')
                ->whereNotNull('query')
                ->distinct()
                ->count('query'),
            'unique_entities_viewed' => DB::table('api_activities')
                ->where('event_type', 'detail_view')
                ->whereNotNull('entity_id')
                ->distinct()
                ->count(DB::raw('CONCAT(entity_type, entity_id)')),
            'last_calculated_at' => now()->toIso8601String(),
        ];
        
        Cache::put('stats.global', $stats, now()->addMinutes(10));
    }
} 