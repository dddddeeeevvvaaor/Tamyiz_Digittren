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
        Schema::create('tanggal_buka_pendaftaran', function (Blueprint $table) {
            $table->integer('id_tglpendaftaran', true);
            $table->datetime('tanggal_buka');
            $table->datetime('tanggal_tutup');
            $table->datetime('tanggal_program');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanggal_buka_pendaftaran');
    }
};
