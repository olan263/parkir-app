<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('parkings', function (Blueprint $table) {
        $table->id();
        $table->string('kode_tiket')->unique();
        $table->string('plat_nomor')->nullable();
        $table->enum('jenis', ['motor', 'mobil']);
        $table->dateTime('waktu_masuk');
        $table->dateTime('waktu_keluar')->nullable();
        $table->integer('total_bayar')->default(0);
        $table->enum('status', ['aktif', 'selesai'])->default('aktif');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parkings');
    }
};
