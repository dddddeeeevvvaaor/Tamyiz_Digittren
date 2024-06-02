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
        Schema::create('santri', function (Blueprint $table) {
            $table->bigInteger('nist')->primary();
            $table->integer('id_user');
            $table->integer('id_program');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['P', 'L']);
            $table->string('city', 15);
            $table->timestamps();
            $table->foreign('id_program')->references('id_program')->on('program')->onDelete('restrict')->onUpdate('cascade'); //jik
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santri');
    }
};
