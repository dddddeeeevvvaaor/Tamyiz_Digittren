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
        Schema::create('setting_tamyiz', function (Blueprint $table) {
            $table->integer('id_settamyiz', true);
            $table->string('nama_pesantren', 30)->nullable();
            $table->char('kode_pos', 5)->nullable();
            $table->string('nomor_telpon', 13)->nullable();
            $table->text('alamat')->nullable();
            $table->string('website', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('logo', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_tamyiz');
    }
};
