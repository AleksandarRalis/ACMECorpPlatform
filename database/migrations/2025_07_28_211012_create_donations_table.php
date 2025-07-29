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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 15, 2)->comment('Donation amount');
            $table->text('message')->nullable()->comment('Donor message');
            $table->boolean('is_anonymous')->default(false)->comment('Anonymous donation flag');
            
            // Relationships
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade')->comment('Campaign being donated to');
            $table->foreignId('donor_id')->constrained('users')->onDelete('cascade')->comment('User making the donation');
            
            // Audit fields
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['campaign_id', 'created_at']);
            $table->index(['donor_id', 'created_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
