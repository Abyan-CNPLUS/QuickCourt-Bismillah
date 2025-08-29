<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat tabel owners
        Schema::create('owners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        // 2. Buat tabel owner_bank_accounts
        Schema::create('owner_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_name');
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('owners')->onDelete('cascade');
        });

        // 3. Tambahkan owner_id ke venues
        Schema::table('venues', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id')->nullable()->after('id');

            $table->foreign('owner_id')->references('id')->on('owners')->onDelete('set null');
        });
    }

    public function down(): void
    {
        // Rollback venues
        Schema::table('venues', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropColumn('owner_id');
        });

        // Drop bank accounts
        Schema::dropIfExists('owner_bank_accounts');

        // Drop owners
        Schema::dropIfExists('owners');
    }
};
