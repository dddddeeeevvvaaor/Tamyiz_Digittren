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
        Schema::create('infaq', function (Blueprint $table) {
            $table->integer('id_infaq', true);
            $table->bigInteger('nist');
            $table->integer('id_bank');
            $table->string('nama_bank', 15);
            $table->string('pemilik_rekening', 50);
            $table->integer('nominal');
            $table->string('img_bukti', 30);
            $table->tinyInteger('status')-> default('0');
            $table->timestamps();
            $table->foreign('nist')->references('nist')->on('santri')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('id_bank')->references('id_bank')->on('bank')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infaq');
    }
};
