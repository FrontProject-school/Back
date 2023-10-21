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
            $table->string('category')->nullable(false);
            $table->string('title')->nullable(false);
            $table->smallInteger('selectNum')->nullable(false);
            $table->string('grade')->nullable(false);
            $table->datetime('rStart')->nullable(false);
            $table->datetime('rEnd')->nullable(false);
            $table->date('actStart')->nullable(false);
            $table->date('actEnd')->nullable(false);
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
