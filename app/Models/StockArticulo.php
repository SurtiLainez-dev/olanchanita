<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockArticulo extends Model
{
    use HasFactory;

    public function sucursal(){
        return $this->belongsTo(Sucursal::class);
    }

    public function articulo(){
        return $this->belongsTo(Articulo::class);
    }
}
