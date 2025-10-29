<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $table = "gastos";
    protected $primaryKey = "idGasto";
    public $incrementing = true;
    public $timestamps = true;
    
    protected $fillable = ['monto', 'formaPago'];
    
    public function usuario(){
        return $this->belongsTo(Usuario::class,'idUsuario','id');
    }
    
    public function transferencia(){
        return $this->hasOne(Transferencia::class, 'gasto_id','idGasto');
    }
}
