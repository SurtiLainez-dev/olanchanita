<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuerpoOrdenEntrada extends Model
{
    use HasFactory;

    public function articulo(){
        return $this->belongsTo(Articulo::class);
    }

    public function estado_articulo(){
        return $this->belongsTo(EstadoArticulo::class);
    }
}
