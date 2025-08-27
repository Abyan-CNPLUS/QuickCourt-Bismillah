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
    Schema::create('venues', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('address');
    $table->integer('capacity');
    $table->string('price');
    $table->string('image')->nullable();
    $table->enum('status', ['available', 'booked'])->default('available');
    $table->enum('approval_status', ['pending','accepted','rejected'])->default('pending'); // untuk admin approval
    $table->unsignedBigInteger('category_id');
    $table->unsignedBigInteger('city_id');
    $table->unsignedBigInteger('user_id'); // id owner
    $table->timestamps();

    $table->foreign('city_id')->references('id')->on('city')->onDelete('cascade');
    $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            //
        });
    }
};
