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
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id_user', true);
            $table->string('nama', 50);
            $table->string('phone', 13);
            $table->string('password', 60);
            $table->enum('role', ['student', 'admin']);
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 15)->nullable();
            $table->string('last_user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
