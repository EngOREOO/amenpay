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
        Schema::create('predictive_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('prediction_type'); // spending_forecast, fraud_probability, churn_prediction, credit_risk
            $table->string('model_version'); // AI model version used
            $table->decimal('confidence_score', 5, 2); // 0.00 to 100.00
            $table->json('input_features'); // Features used for prediction
            $table->json('prediction_output'); // Raw prediction results
            $table->json('feature_importance'); // Feature importance scores
            $table->json('prediction_interval'); // Confidence intervals
            $table->json('historical_comparison'); // Comparison with historical data
            $table->json('trend_analysis'); // Trend identification
            $table->json('seasonal_patterns'); // Seasonal pattern detection
            $table->json('anomaly_detection'); // Anomaly identification
            $table->json('risk_assessment'); // Risk scoring
            $table->json('recommendations'); // AI-generated recommendations
            $table->json('action_items'); // Suggested actions
            $table->json('performance_metrics'); // Model performance metrics
            $table->json('accuracy_metrics'); // Prediction accuracy
            $table->json('bias_detection'); // Bias and fairness analysis
            $table->json('explainability'); // Model explainability
            $table->string('status'); // active, inactive, deprecated
            $table->timestamp('prediction_date');
            $table->timestamp('valid_until')->nullable();
            $table->timestamp('last_updated')->nullable();
            $table->json('model_metadata'); // Model training and deployment info
            $table->json('data_sources'); // Data sources used
            $table->json('preprocessing_steps'); // Data preprocessing steps
            $table->json('validation_results'); // Cross-validation results
            $table->json('drift_detection'); // Data drift detection
            $table->json('model_performance'); // Ongoing performance monitoring
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'prediction_type']);
            $table->index(['prediction_type', 'status']);
            $table->index(['confidence_score', 'prediction_date']);
            $table->index(['status', 'prediction_date']);
            $table->index(['model_version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predictive_analytics');
    }
};
