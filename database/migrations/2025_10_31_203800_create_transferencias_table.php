<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transferencias', function (Blueprint $table) {
            $table->id('idTransferencia'); // Primary Key
            $table->string('alias'); // Requisito: alias
            $table->string('nombreDestinatario'); // Requisito: nombre de la persona
            
            // Relación con Gasto
            $table->foreignId('gasto_id')->constrained('gastos', 'idGasto')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transferencias');
    }
};
