<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transferencias', function (Blueprint $table) {
            $table->id('idTransferencia'); // Clave primaria
            $table->string('alias'); // Alias de la cuenta
            $table->string('nombreDestinatario')->nullable(); // ✅ Ahora sí: campo nuevo, puede ser null
            $table->foreignId('gasto_id') // Relación con Gasto
                  ->constrained('gastos', 'idGasto')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transferencias');
    }
};

