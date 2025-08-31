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
        Schema::create('push_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('device_token'); // FCM or APNS token
            $table->string('platform'); // ios, android, web
            $table->string('app_version')->nullable();
            $table->string('device_model')->nullable();
            $table->string('os_version')->nullable();
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('preferences')->nullable(); // Notification preferences for this device
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['device_token', 'platform']);
            $table->index(['status', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_notifications');
    }
};
