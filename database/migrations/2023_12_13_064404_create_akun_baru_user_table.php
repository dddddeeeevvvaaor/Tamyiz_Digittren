<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('akun_baru_user', function (Blueprint $table) {
            $table->integer('id_newuser', true);
            $table->integer('id_payment');
            $table->integer('id_golongan');
            $table->string('firstname', 25);
            $table->string('lastname', 25);
            $table->string('username', 13);
            $table->string('email', 50)->unique();
            $table->string('password', 60);
            $table->string('city', 15);
            $table->enum('role', ['student', 'admin']);
            $table->enum('status', ['active', 'inactive']);
            $table->timestamp('mulai')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('berakhir')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();

            $table->foreign('id_payment')->references('id_payment')->on('payments')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('id_golongan')->references('id_golongan')->on('golongan')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun_baru_user');
    }
};
