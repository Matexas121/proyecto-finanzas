<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transferencia extends Model
{
    use HasFactory;
    
    protected $table = "transferencias";
    protected $primaryKey = "idTransferencia";
    public $incrementing = true;
    public $timestamps= true;
    
    protected $fillable = ["alias", "gasto_id", "nombreDestinarario", "idTransferencia"];
    
    public function gasto(){
        return $this->belongsTo(Gasto::class,'gasto_id','idGasto');
    }
}
