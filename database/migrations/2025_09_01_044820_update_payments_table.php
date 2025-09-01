<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Biar payment_date bisa kosong saat pending/manual
            $table->dateTime('payment_date')->nullable()->change();

            // Tambah proof untuk bukti transfer
            if (!Schema::hasColumn('payments', 'proof')) {
                $table->string('proof')->nullable()->after('status');
            }

            // Ubah enum status → kalau MySQL biasa belum support ubah enum langsung
            // Jadi kita bisa pakai string dulu
            $table->string('status')->default('pending')->change();
        });
    }

   public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('proof');
        });
    }
};
