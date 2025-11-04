<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gastos', function (Blueprint $table) {
            $table->id('idGasto'); // Primary Key
            $table->decimal('monto', 10, 2);
            $table->date('fecha'); // Requisito: Fecha
            $table->string('descripcion')->nullable(); // Requisito: Descripción opcional
            $table->enum('formaPago', ['debito', 'efectivo', 'credito', 'transferencia']); // Requisito: Forma de pago
            $table->integer('dniUsuario')->nullable();
            // Relación con Usuario (D1)
            $table->foreignId('idUsuario')->constrained('users')->onDelete('cascade');

            // Relación con Categoría (D1)
            $table->foreignId('idCategoria')->constrained('categorias', 'idCategoria')->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
