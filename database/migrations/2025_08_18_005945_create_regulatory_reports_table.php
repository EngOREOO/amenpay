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
        Schema::create('regulatory_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type'); // suspicious_activity, large_transactions, currency_transactions, compliance
            $table->string('reporting_authority'); // SAMA, AMLA, local_authorities
            $table->string('reporting_frequency'); // daily, weekly, monthly, quarterly, annually, on_demand
            $table->string('status'); // draft, submitted, accepted, rejected, under_review
            $table->string('priority'); // low, medium, high, urgent
            $table->json('report_data'); // Structured data for regulatory submission
            $table->json('compliance_metrics'); // Key compliance indicators
            $table->json('risk_indicators'); // Risk assessment data
            $table->json('transaction_summaries'); // Transaction aggregation data
            $table->json('user_activity_summaries'); // User behavior summaries
            $table->json('fraud_incidents'); // Fraud detection summaries
            $table->json('aml_activities'); // Anti-money laundering activities
            $table->json('kyc_summaries'); // KYC verification summaries
            $table->json('sanctions_screening'); // Sanctions list screening results
            $table->json('pep_identifications'); // Politically exposed person identifications
            $table->json('suspicious_patterns'); // Suspicious activity patterns
            $table->json('regulatory_flags'); // Regulatory compliance flags
            $table->json('audit_trail'); // Complete audit trail
            $table->timestamp('reporting_period_start')->nullable();
            $table->timestamp('reporting_period_end')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('rejection_reason')->nullable();
            $table->json('regulatory_feedback')->nullable(); // Feedback from regulatory authorities
            $table->json('compliance_actions')->nullable(); // Actions taken based on feedback
            $table->json('follow_up_reports')->nullable(); // Related follow-up reports
            $table->json('regulatory_requirements')->nullable(); // Specific regulatory requirements
            $table->json('data_retention_policy')->nullable(); // Data retention compliance
            $table->timestamps();

            // Indexes for performance
            $table->index(['report_type', 'status']);
            $table->index(['reporting_authority', 'status']);
            $table->index(['status', 'due_date']);
            $table->index(['reporting_period_start', 'reporting_period_end'], 'idx_regulatory_period');
            $table->index(['submitted_by']);
            $table->index(['reviewed_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulatory_reports');
    }
};
