<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria; // Importa tu modelo Categoria
use Illuminate\Support\Facades\DB; // Opcional, pero útil si no usas el modelo

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Array con las categorías iniciales necesarias
        $categorias = [
            ['nombre' => 'Comidas y bebidas'],
            ['nombre' => 'Transporte'],
            ['nombre' => 'Entretenimiento'],
            ['nombre' => 'Vivienda'],
            ['nombre' => 'Servicios'], // Ej: Luz, agua, internet
            ['nombre' => 'Salud'],
            ['nombre' => 'Transferencia'], // Para diferenciar transferencias de gastos puros
            ['nombre' => 'Otros'],
        ];

        // 2. Insertar los datos en la tabla 'categorias'
        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
            
            // Alternativa: Si usas DB, no necesitas el modelo:
            // DB::table('categorias')->insert($categoria);
        }
    }
}
