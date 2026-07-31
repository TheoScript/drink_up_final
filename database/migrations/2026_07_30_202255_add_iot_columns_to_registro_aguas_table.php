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
    Schema::table('registros_agua', function (Blueprint $table) {
        // 'manual_web' será o padrão para quando o usuário digitar no site
        $table->string('origem')->default('manual_web'); 
        
        // Conecta o registro ao bebedouro (pode ser nulo se foi manual)
        $table->foreignId('bebedouro_id')->nullable()->constrained('bebedouros')->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registros_agua', function (Blueprint $table) {
            //
        });
    }
};
