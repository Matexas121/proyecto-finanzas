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
        Schema::table('gastos', function (Blueprint $table) {
            // 
            $table->string('dniUsuario')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gastos', function (Blueprint $table) {
            // Revierte el cambio (vuelve a ser NOT NULL, ajusta el tipo si es necesario)
            // Esto podría causar problemas si ya hay NULLs, úsalo con precaución.
            // $table->string('dniUsuario')->nullable(false)->change();
        });
    }
};
