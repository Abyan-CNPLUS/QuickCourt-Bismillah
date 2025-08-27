<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            if (!Schema::hasColumn('venues', 'approval_status')) {
                $table->enum('approval_status', ['pending', 'accepted', 'rejected'])
                      ->default('pending')
                      ->after('status');
            }

            if (!Schema::hasColumn('venues', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('city_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            if (Schema::hasColumn('venues', 'approval_status')) {
                $table->dropColumn('approval_status');
            }
            if (Schema::hasColumn('venues', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
