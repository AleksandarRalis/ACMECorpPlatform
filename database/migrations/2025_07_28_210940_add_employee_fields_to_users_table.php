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
            $table->date('hire_date')->nullable()->after('position')->comment('Employee hire date');
            $table->boolean('is_active')->default(true)->after('hire_date')->comment('Account active status');
            $table->string('profile_photo')->nullable()->after('is_active')->comment('Profile photo URL');
            $table->string('phone')->nullable()->after('profile_photo')->comment('Employee phone number');
            $table->text('bio')->nullable()->after('phone')->comment('Employee bio/description');
            $table->json('preferences')->nullable()->after('bio')->comment('User preferences as JSON');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    
    }
};
