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
        Schema::create('akuntansi', function (Blueprint $table) {
            $table->integer('id_akuntan', true);
            $table->integer('id_payment')->nullable();
            $table->integer('id_infaq')->nullable();
            $table->datetime('tanggal');
            $table->string('keterangan_program', 20) -> nullable();
            $table->string('keterangan_infaq', 50) -> nullable();
            $table->integer('debet');
            $table->integer('saldo');
            $table->timestamps();
            $table->foreign('id_payment')->references('id_payment')->on('payments')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('id_infaq')->references('id_infaq')->on('infaq')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akuntansi');
    }
};
