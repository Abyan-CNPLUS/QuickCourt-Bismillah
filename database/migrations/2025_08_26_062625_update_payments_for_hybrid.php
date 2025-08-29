<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'payment_method')) {
                $table->enum('payment_method', ['gateway','manual'])->default('gateway')->after('amount');
            } else {
                $table->enum('payment_method', ['gateway','manual'])->default('gateway')->change();
            }

            if (!Schema::hasColumn('payments', 'payment_proof')) {
                $table->string('payment_proof')->nullable()->after('payment_method');
            }

            if (!Schema::hasColumn('payments', 'snap_token')) {
                $table->string('snap_token')->nullable()->after('payment_proof');
            }

            // update status enum supaya mendukung 'waiting_validation'
            $table->enum('status', ['pending','waiting_validation','success','failed'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('payments', 'payment_proof')) {
                $table->dropColumn('payment_proof');
            }
            if (Schema::hasColumn('payments', 'snap_token')) {
                $table->dropColumn('snap_token');
            }

            $table->enum('status', ['pending','confirmed','cancelled','completed'])->default('pending')->change();
        });
    }
};
