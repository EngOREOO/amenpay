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
        Schema::create('spending_patterns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('pattern_type'); // daily, weekly, monthly, seasonal, event_based
            $table->json('pattern_data'); // Pattern-specific data and metrics
            $table->decimal('confidence_score', 5, 2); // AI confidence in pattern (0-100)
            $table->json('predictions')->nullable(); // Future spending predictions
            $table->json('anomalies')->nullable(); // Detected spending anomalies
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_updated');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'pattern_type']);
            $table->index(['user_id', 'category_id']);
            $table->index(['confidence_score', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spending_patterns');
    }
};
