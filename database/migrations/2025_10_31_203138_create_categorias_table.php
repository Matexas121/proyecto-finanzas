<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id('idCategoria'); // Clave primaria
            $table->string('nombre', 50)->unique(); // Ej: Alimentación, Transporte, Ocio
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
