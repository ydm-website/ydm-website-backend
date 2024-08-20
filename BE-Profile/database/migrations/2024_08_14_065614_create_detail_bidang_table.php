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
        Schema::create('detail_bidang', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('deskripsi');
            $table->string('image')->nullable();
            $table->uuid('bidang_id');
            $table->foreign('bidang_id')->references('id')->on('bidang')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_bidang');
    }
};
