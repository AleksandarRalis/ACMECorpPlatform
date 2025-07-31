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
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_id')->unique()->after('id')->comment('Unique company employee ID');
            $table->string('department')->nullable()->after('email')->comment('Employee department');
            $table->string('position')->nullable()->after('department')->comment('Employee position/title');
            $table->boolean('is_active')->default(true)->after('position')->comment('Account active status');
            $table->string('phone')->nullable()->after('is_active')->comment('Employee phone number');
            $table->foreignId('role_id')->constrained('roles')->after('phone')->comment('Role ID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    
    }
};
