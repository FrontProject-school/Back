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
        Schema::create('recruit_departs', function (Blueprint $table) {
            $table->id();
            $table->string('pId')->nullable(false);
            $table->string('depart')->nullable(false);
            $table->timestamps();
            $table->foreign('pId')->references('pId')->on('programs')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruit_departs');
    }
};
