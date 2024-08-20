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
        Schema::create('pengurus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('jabatan');
            $table->uuid('peran_id');
            $table->foreign('peran_id')->references('id')->on('peran_pengurus')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengurus');
    }
};
