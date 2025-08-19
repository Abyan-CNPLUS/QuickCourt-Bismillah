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
        Schema::create('fnb_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fnb_order_id');
            $table->unsignedBigInteger('fnb_menu_id');
            $table->integer('quantity');
            $table->integer('price');
            $table->timestamps();

            $table->foreign('fnb_order_id')->references('id')->on('fnb_orders')->onDelete('cascade');
            $table->foreign('fnb_menu_id')->references('id')->on('fnb_menu')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fnb_order_items');
    }
};
