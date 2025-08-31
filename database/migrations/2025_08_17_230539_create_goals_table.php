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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name_ar');
            $table->string('name_en');
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->decimal('target_amount', 15, 2); // Target savings amount
            $table->decimal('current_amount', 15, 2)->default(0); // Current saved amount
            $table->decimal('progress_percentage', 5, 2)->default(0); // Progress percentage
            $table->enum('type', ['savings', 'purchase', 'investment', 'emergency', 'other'])->default('savings');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->date('target_date')->nullable(); // Target completion date
            $table->enum('status', ['active', 'completed', 'paused', 'cancelled'])->default('active');
            $table->string('icon')->nullable(); // Goal icon
            $table->string('color', 7)->nullable(); // Goal color (hex)
            $table->json('milestones')->nullable(); // Milestone tracking
            $table->json('notifications')->nullable(); // Notification preferences
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'type']);
            $table->index(['target_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
