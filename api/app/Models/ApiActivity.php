<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',       // 'search', 'detail_view', etc.
        'entity_type',      // 'character', 'movie', 'planet', etc.
        'entity_id',        // ID of the entity if applicable
        'entity_name',      // Name of the entity if applicable
        'query',            // Search query if applicable
        'response_time',    // Response time in seconds
        'user_id',          // Optional user ID for authenticated requests
        'metadata',         // JSON column for additional data
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];
} 