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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name_ar');
            $table->string('name_en');
            $table->decimal('amount', 15, 2); // Budget amount
            $table->decimal('spent', 15, 2)->default(0); // Amount spent so far
            $table->decimal('remaining', 15, 2); // Remaining budget
            $table->enum('period', ['daily', 'weekly', 'monthly', 'yearly'])->default('monthly');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'completed', 'overdue', 'cancelled'])->default('active');
            $table->json('notifications')->nullable(); // Notification preferences
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'category_id']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
