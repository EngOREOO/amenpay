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
        Schema::create('kyc_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('verification_type'); // identity, address, income, source_of_funds, business
            $table->string('status'); // pending, submitted, reviewing, approved, rejected, expired
            $table->string('verification_level'); // basic, enhanced, premium
            $table->json('identity_documents'); // National ID, passport, driving license
            $table->json('address_proof'); // Utility bills, bank statements, rental agreements
            $table->json('income_documents'); // Salary slips, tax returns, bank statements
            $table->json('source_of_funds'); // Employment, business, inheritance, investment
            $table->json('business_documents')->nullable(); // Business registration, licenses
            $table->json('compliance_checks'); // SAMA, AML, sanctions screening
            $table->json('risk_assessment'); // Risk scoring and categorization
            $table->json('verification_steps'); // Completed verification steps
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('rejection_reason')->nullable();
            $table->json('additional_requirements')->nullable();
            $table->json('aml_flags')->nullable(); // Anti-money laundering flags
            $table->json('sanctions_matches')->nullable(); // Sanctions list matches
            $table->json('pep_checks')->nullable(); // Politically exposed person checks
            $table->json('adverse_media')->nullable(); // Negative media coverage
            $table->json('regulatory_flags')->nullable(); // SAMA regulatory flags
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['verification_type', 'status']);
            $table->index(['status', 'submitted_at']);
            $table->index(['expires_at']);
            $table->index(['reviewed_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_verifications');
    }
};
