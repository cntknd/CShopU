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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id'); // bigint unsigned auto increment primary key
            $table->unsignedBigInteger('user_id')->nullable(); // foreign key (optional)
            $table->decimal('total_price', 10, 2)->nullable();
            $table->string('status', 20)->default('pending');
            $table->dateTime('created_at')->useCurrent(); // default current_timestamp
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }

};
