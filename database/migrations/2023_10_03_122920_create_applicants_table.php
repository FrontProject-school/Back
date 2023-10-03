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
        Schema::create('applicants', function (Blueprint $table) {
            $table->smallInteger('num')->autoIncrement();
            $table->string('stuId')->nullable(false);
            $table->string('program', 50)->nullable(false);
            $table->string('answer', 200)->nullable(false);
            $table->timestamps();

            $table->foreign('stuId')->references('stuId')->on('users')->cascadeOnDelete();
            $table->foreign('program')->references('num')->on('programs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
