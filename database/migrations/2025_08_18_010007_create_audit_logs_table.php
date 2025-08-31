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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('audit_type'); // user_action, system_event, security_event, compliance_event
            $table->string('event_category'); // authentication, authorization, data_access, data_modification, system_config
            $table->string('severity'); // low, medium, high, critical
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('user_type'); // admin, user, system, api
            $table->string('action'); // create, read, update, delete, login, logout, access, modify
            $table->string('resource_type'); // user, transaction, wallet, card, setting, report
            $table->unsignedBigInteger('resource_id')->nullable();
            $table->json('resource_data'); // Resource data before/after changes
            $table->json('request_data'); // HTTP request data
            $table->json('response_data'); // HTTP response data
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('session_id')->nullable();
            $table->json('device_info')->nullable(); // Device fingerprinting
            $table->json('location_info')->nullable(); // Geographic location
            $table->json('risk_indicators')->nullable(); // Risk assessment
            $table->json('compliance_flags')->nullable(); // Compliance flags
            $table->json('audit_context'); // Additional context information
            $table->json('related_events')->nullable(); // Related audit events
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Additional metadata
            $table->timestamp('event_timestamp');
            $table->timestamp('processed_at')->nullable();
            $table->string('processing_status')->default('pending'); // pending, processed, failed
            $table->json('processing_result')->nullable(); // Processing results
            $table->json('retention_policy')->nullable(); // Data retention policy
            $table->json('archival_info')->nullable(); // Archival information
            $table->timestamps();

            // Indexes for performance
            $table->index(['audit_type', 'event_category']);
            $table->index(['user_id', 'event_timestamp']);
            $table->index(['severity', 'event_timestamp']);
            $table->index(['resource_type', 'resource_id']);
            $table->index(['action', 'event_timestamp']);
            $table->index(['event_timestamp']);
            $table->index(['processing_status']);
            $table->index(['ip_address', 'event_timestamp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
