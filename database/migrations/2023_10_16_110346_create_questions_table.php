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
        Schema::create('questions', function (Blueprint $table) {
            $table->id('');
            $table->string('studId')->nullable(false);
            $table->string('title', 50)->nullable(false);
            $table->string('content', 500)->nullable(false);
            $table->string('answer', 500)->nullable();
            $table->string('secret', 1)->nullable(false);
            $table->timestamps();

            $table->foreign('studId')->references('studId')->on('users')->onUpdate('cascade')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
