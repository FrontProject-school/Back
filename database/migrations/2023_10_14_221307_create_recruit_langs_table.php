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
        Schema::create('recruit_langs', function (Blueprint $table) {
            $table->id();
            $table->string('program', 100);
            $table->string('lang', 100);
            $table->timestamps();
            $table->foreign('program')->references('pId')->on('programs')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruit_langs');
    }
};
