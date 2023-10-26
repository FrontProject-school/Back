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
        Schema::create('applicant_answers', function (Blueprint $table) {
            $table->id();
            $table->string('pId');
            $table->string('studId');
            $table->tinyInteger('aNumber');
            $table->string('answer');
            $table->timestamps();

            $table->foreign('pId')->references('pId')->on('programs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('studId')->references('studId')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_answers');
    }
};
