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
    Schema::create('bebedouros', function (Blueprint $table) {
        $table->id();
        // Conecta o bebedouro ao usuário dono dele
        $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
        $table->string('mac_address')->unique();
        $table->string('nome')->nullable(); // Ex: Bebedouro da Cozinha
        $table->boolean('status_online')->default(false);
        $table->timestamps();
    });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bebedouros');
    }
};
