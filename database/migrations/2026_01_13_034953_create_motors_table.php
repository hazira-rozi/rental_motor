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
        Schema::create('motors', function (Blueprint $table) {
    $table->id();
    $table->string('nopol', 15)->unique(); // Nomor Polisi
    $table->string('merk');
    $table->integer('harga_sewa');
    $table->enum('status', ['tersedia', 'disewa','booking'])->default('tersedia');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motors');
    }
};
