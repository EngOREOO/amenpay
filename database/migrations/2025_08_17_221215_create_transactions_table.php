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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['payment', 'transfer', 'deposit', 'withdrawal', 'refund']);
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('SAR');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('reference_id', 100)->unique()->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
