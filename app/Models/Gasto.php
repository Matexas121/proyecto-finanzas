<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transferencia;
use App\Models\Categoria; // Importamos el modelo Categoria

class Gasto extends Model
{
    protected $table = "gastos";
    protected $primaryKey = "idGasto";
    public $incrementing = true;
    public $timestamps = true;

    // Campos permitidos para la asignación masiva desde formularios
    protected $fillable = [
        'monto', 
        'fecha',              
        'descripcion',        
        'formaPago', 
        'idUsuario',          
        'idCategoria',        
    ];
    
    // Relación: Gasto pertenece a Usuario (belongsTo)
    public function usuario(){
        return $this->belongsTo(\App\Models\User::class,'idUsuario','id');
    }
    
    // Relación: Gasto pertenece a Categoria (belongsTo)
    public function categoria(){
        return $this->belongsTo(Categoria::class,'idCategoria','idCategoria');
    }
    
    // Relación: Gasto tiene una Transferencia (hasOne)
    public function transferencia(){
        return $this->hasOne(Transferencia::class, 'gasto_id','idGasto');
    }
}