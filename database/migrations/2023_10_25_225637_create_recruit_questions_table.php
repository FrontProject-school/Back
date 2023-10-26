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
        Schema::create('recruit_questions', function (Blueprint $table) {
            $table->id();
            $table->string('pId');
            $table->tinyInteger('qNumber');
            $table->string('question');
            $table->timestamps();

            $table->foreign('pId')->references('pId')->on('programs')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruit_questions');
    }
};
