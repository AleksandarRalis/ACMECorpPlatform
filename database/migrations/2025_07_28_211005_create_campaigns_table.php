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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Campaign title');
            $table->text('description')->comment('Campaign description');
            $table->string('category')->comment('Campaign category (environment, education, health, etc.)');
            $table->decimal('goal_amount', 15, 2)->comment('Campaign funding goal');
            $table->decimal('current_amount', 15, 2)->default(0)->comment('Current amount raised');
            $table->string('image_url')->nullable()->comment('Campaign image URL');
            $table->date('start_date')->comment('Campaign start date');
            $table->date('end_date')->comment('Campaign end date');
            $table->enum('status', ['active', 'pending', 'completed', 'cancelled'])->default('pending')->comment('Campaign status');
            
            // Relationships
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade')->comment('Campaign creator');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->comment('Who approved the campaign');
            $table->timestamp('approved_at')->nullable()->comment('When campaign was approved');
            
            // Audit fields
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['status', 'end_date']);
            $table->index(['category', 'status']);
            $table->index(['created_by', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
