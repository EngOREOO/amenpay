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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en');
            $table->text('content_ar');
            $table->text('content_en');
            $table->enum('type', ['info', 'warning', 'success', 'error', 'maintenance'])->default('info');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['draft', 'active', 'scheduled', 'expired', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('target_audience')->nullable(); // User segments, languages, etc.
            $table->json('delivery_channels')->nullable(); // push, sms, email, in_app
            $table->boolean('requires_acknowledgment')->default(false);
            $table->integer('acknowledged_count')->default(0);
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['status', 'published_at']);
            $table->index(['type', 'priority']);
            $table->index(['expires_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
