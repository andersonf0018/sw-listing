<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_activities', function (Blueprint $table) {
            $table->id();
            $table->string('event_type');                // 'search', 'detail_view', etc.
            $table->string('entity_type')->nullable();   // 'character', 'movie', 'planet', etc.
            $table->string('entity_id')->nullable();     // ID of the entity if applicable
            $table->string('entity_name')->nullable();   // Name of the entity if applicable
            $table->string('query')->nullable();         // Search query if applicable
            $table->float('response_time')->nullable();  // Response time in seconds
            $table->string('user_id')->nullable();       // Optional user ID for authenticated requests
            $table->json('metadata')->nullable();        // Additional data in JSON format
            $table->timestamps();
            
            // Add indexes for common queries
            $table->index('event_type');
            $table->index(['entity_type', 'entity_id']);
            $table->index('query');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_activities');
    }
}; 