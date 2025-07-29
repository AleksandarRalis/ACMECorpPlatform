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
        Schema::create('user_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('User ID');
            $table->foreignId('role_id')->constrained()->onDelete('cascade')->comment('Role ID');
            $table->timestamp('assigned_at')->useCurrent()->comment('When role was assigned');
            $table->timestamps();
            
            $table->unique('user_id'); // One role per user
            $table->index(['user_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
