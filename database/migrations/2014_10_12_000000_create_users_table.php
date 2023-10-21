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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('studId')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->tinyInteger('grade');
            $table->string('depart');
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('snsId')->nullable();
            $table->string('gender');
            $table->dateTime('birth');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
