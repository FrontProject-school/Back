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
        Schema::create('programs', function (Blueprint $table) {
            $table->string('pId')->primary();
            $table->string('category');
            $table->string('title');
            $table->smallInteger('selectNum');
            $table->string('grade');
            $table->datetime('rStart');
            $table->datetime('rEnd');
            $table->date('actStart');
            $table->date('actEnd');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
