<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['message', 'rating']);
            
            // Add new survey fields
            $table->string('client_type');
            $table->string('sex');
            $table->integer('age');
            $table->string('email')->nullable();
            
            // Service Quality Dimensions
            $table->integer('SQD1')->nullable();
            $table->integer('SQD2')->nullable();
            $table->integer('SQD3')->nullable();
            $table->integer('SQD4')->nullable();
            $table->integer('SQD5')->nullable();
            $table->integer('SQD6')->nullable();
            $table->integer('SQD7')->nullable();
            $table->integer('SQD8')->nullable();
            
            $table->text('suggestions')->nullable();
        });
    }

    public function down()
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            // Restore old columns
            $table->text('message');
            $table->integer('rating');
            
            // Remove new columns
            $table->dropColumn([
                'client_type',
                'sex',
                'age',
                'email',
                'SQD1',
                'SQD2',
                'SQD3',
                'SQD4',
                'SQD5',
                'SQD6',
                'SQD7',
                'SQD8',
                'suggestions'
            ]);
        });
    }
};