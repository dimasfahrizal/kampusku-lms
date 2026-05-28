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
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('mahasiswa'); // mahasiswa, dosen
            }
        });

        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'invite_code')) {
                $table->string('invite_code')->unique()->nullable();
            }
            if (!Schema::hasColumn('courses', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });

        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('courses', 'invite_code')) {
                $table->dropColumn('invite_code');
            }
        });
    }
};