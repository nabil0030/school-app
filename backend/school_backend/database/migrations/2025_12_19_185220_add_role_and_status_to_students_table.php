<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {

            // Add role
            $table->enum('role', ['STUDENT', 'ADMIN'])
                  ->default('STUDENT')
                  ->after('bac_image');

            // Add status
            $table->enum('status', ['pending', 'accepted', 'rejected'])
                  ->default('pending')
                  ->after('role');

            // Optional but recommended
            $table->text('admin_comment')
                  ->nullable()
                  ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['role', 'status', 'admin_comment']);
        });
    }
};
