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
        Schema::create('freeboards', function (Blueprint $table) {
            $table->id();
            $table->string('studId')->nullable(false);
            $table->string('title', 50)->nullable(false);
            $table->string('content', 1000)->nullable(false);
            
            $table->timestamps();

            $table->foreign('studId')->references('studId')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freeboards');
    }
};
