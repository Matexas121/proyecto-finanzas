<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = "categorias";
    protected $primaryKey = "idCategoria";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['nombre'];

    public function gastos(){
        // Una categoría tiene muchos gastos
        return $this->hasMany(Gasto::class, 'idCategoria', 'idCategoria');
    }
}
