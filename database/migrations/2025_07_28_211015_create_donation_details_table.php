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
        Schema::create('donation_details', function (Blueprint $table) {
            $table->id();
            
            // Payment processing data
            $table->string('payment_method')->comment('Payment method used (credit_card, bank_transfer, paypal, etc.)');
            $table->string('transaction_id')->unique()->comment('External payment transaction ID');
            $table->enum('payment_status', ['pending', 'processing', 'completed', 'failed', 'refunded', 'cancelled'])->default('pending')->comment('Payment processing status');
            $table->json('gateway_response')->nullable()->comment('Raw response from payment gateway');
            $table->json('metadata')->nullable()->comment('Additional payment metadata');
            
            // Processing timestamps
            $table->timestamp('processed_at')->nullable()->comment('When payment was processed');
            $table->timestamp('failed_at')->nullable()->comment('When payment failed');
            $table->text('failure_reason')->nullable()->comment('Reason for payment failure');
            
            // Relationships
            $table->foreignId('donation_id')->constrained('donations')->onDelete('cascade')->comment('Associated donation');
            $table->foreignId('donor_id')->nullable()->constrained('users')->onDelete('set null')->comment('Who processed the payment');
            
            // Audit fields
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['payment_status', 'processed_at']);
            $table->index(['payment_method', 'payment_status']);
            $table->index('transaction_id');
            $table->index('donation_id');
            $table->index('donor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
}; 