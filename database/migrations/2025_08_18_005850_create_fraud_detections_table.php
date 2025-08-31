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
        Schema::create('fraud_detections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_id')->nullable()->constrained()->onDelete('set null');
            $table->string('fraud_type'); // transaction_fraud, account_takeover, identity_theft, money_laundering
            $table->string('risk_level'); // low, medium, high, critical
            $table->decimal('risk_score', 5, 2); // 0.00 to 100.00
            $table->json('ai_analysis'); // AI model predictions and confidence scores
            $table->json('fraud_indicators'); // List of detected fraud indicators
            $table->json('behavioral_patterns'); // User behavior analysis
            $table->json('location_analysis'); // Geographic risk assessment
            $table->json('device_analysis'); // Device fingerprinting
            $table->json('network_analysis'); // Network and IP analysis
            $table->json('temporal_patterns'); // Time-based patterns
            $table->json('amount_patterns'); // Transaction amount patterns
            $table->json('velocity_patterns'); // Transaction frequency patterns
            $table->json('relationship_analysis'); // Connection to other users/accounts
            $table->string('status'); // pending, investigating, confirmed, false_positive, resolved
            $table->text('investigation_notes')->nullable();
            $table->json('mitigation_actions')->nullable(); // Actions taken to prevent fraud
            $table->timestamp('detected_at');
            $table->timestamp('investigated_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('investigated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->json('compliance_flags')->nullable(); // SAMA, AML compliance flags
            $table->json('regulatory_reports')->nullable(); // Links to regulatory reports
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['fraud_type', 'risk_level']);
            $table->index(['risk_score', 'detected_at']);
            $table->index(['status', 'detected_at']);
            $table->index(['transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fraud_detections');
    }
};
