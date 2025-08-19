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
        Schema::create('fnb_menu', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('categories_id');
            $table->unsignedBigInteger('venue_id');
            $table->integer('price');
            $table->string('description')->nullable();
            $table->string('image');
            $table->timestamps();
            
            $table->foreign('venue_id')->references('id')->on('venues')->onDelete('cascade');
            $table->foreign('categories_id')->references('id')->on('fnb_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fnb');
    }
};
