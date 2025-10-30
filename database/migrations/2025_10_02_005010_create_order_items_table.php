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
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->unsignedBigInteger('order_id')->nullable(); // FK to orders
            $table->unsignedBigInteger('product_id')->nullable(); // FK to products
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->nullable();
            $table->string('size', 50)->nullable();
            $table->timestamps(); // created_at + updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }

};
