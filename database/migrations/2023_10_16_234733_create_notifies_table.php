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
        Schema::create('notifies', function (Blueprint $table) {
            $table->id();
            $table->string('stuId');
            $table->string('check',1)->default('N');
            $table->string('pId');
            $table->timestamps();

            $table->foreign('stuId')->references('stuId')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pId')->references('pId')->on('program')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifies');
    }
};
