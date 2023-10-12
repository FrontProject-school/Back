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
        Schema::create('recruits', function (Blueprint $table) {
            $table->id();
            $table->string('program', 50);
            $table->string('depart', 100);
            $table->string('lang', 50);
            $table->string('grade', 1);
            $table->timestamps();

            $table->foreign('program')->references('pId')->on('programs')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruits');
    }
};
