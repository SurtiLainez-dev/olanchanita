<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPadre extends Model
{
    use HasFactory;

    public function articulo(){
        return $this->belongsTo(Articulo::class);
    }

    public function articulo_padre(){
        return $this->belongsTo(Articulo::class,'articulo_padre_id','id');
    }
}
