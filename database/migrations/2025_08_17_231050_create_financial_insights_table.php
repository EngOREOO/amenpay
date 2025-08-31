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
        Schema::create('financial_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // spending_analysis, savings_opportunity, risk_alert, trend_prediction
            $table->string('title_ar');
            $table->string('title_en');
            $table->text('description_ar');
            $table->text('description_en');
            $table->json('data'); // Insight-specific data
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('category', ['spending', 'savings', 'investment', 'risk', 'opportunity'])->default('spending');
            $table->boolean('is_actionable')->default(true);
            $table->json('actions')->nullable(); // Suggested actions
            $table->timestamp('insight_date');
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'severity']);
            $table->index(['user_id', 'category']);
            $table->index(['insight_date', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_insights');
    }
};
