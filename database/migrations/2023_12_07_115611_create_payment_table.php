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
        Schema::create('payments', function (Blueprint $table) {
            $table->integer('id_payment', true);
            $table->bigInteger('nist');
            $table->integer('id_bank');
            $table->integer('id_periodedaftar');
            $table->string('nama_bank', 15);
            $table->string('pemilik_rekening', 50);
            $table->integer('nominal');
            $table->string('img_bukti', 30);
            $table->tinyInteger('status')-> default('0');
            $table->integer('infaq')->nullable();
            $table->integer('jumlah_pembayaran');
            $table->timestamps();
            $table->foreign('nist')->references('nist')->on('santri')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('id_bank')->references('id_bank')->on('bank')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('id_periodedaftar')->references('id_periodedaftar')->on('periode_pendaftaran')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
