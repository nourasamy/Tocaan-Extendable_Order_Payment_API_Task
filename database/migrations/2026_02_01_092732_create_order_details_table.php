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
        Schema::create('order_details', function (Blueprint $table) {
           $table->id();
           $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
           $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
           $table->decimal('price', 10, 2); // Price of the item at the time of order
           $table->integer('qty');
           $table->decimal('total', 10, 2);
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
