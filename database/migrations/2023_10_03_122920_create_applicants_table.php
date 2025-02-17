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
            $table->id();
            $table->string('studId');
            $table->string('pId');
            $table->string('selected', 1);
            $table->timestamps();

            $table->foreign('studId')->references('studId')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('pId')->references('pId')->on('programs')->cascadeOnUpdate()->cascadeOnDelete();
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
