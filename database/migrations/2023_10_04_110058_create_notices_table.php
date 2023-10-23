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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adminId')->nullable();
            $table->string('title', 50)->nullable(false);
            $table->string('content', 1000)->nullable(false);
            $table->string('confirm', 1)->nullable(false);
            $table->timestamps();

            $table->foreign('adminId')->references('id')->on('admins')->cascadeOnUpdate()->cascadeOnDelete();

         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
