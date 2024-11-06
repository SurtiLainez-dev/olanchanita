<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;

    public function marca(){
        return $this->belongsTo(Marca::class);
    }

    public function sub_familia_articulo(){
        return $this->belongsTo(SubFamiliaArticulo::class);
    }

    public function precio_articulos(){
        return $this->hasMany(PrecioArticulo::class);
    }

    public function stock_articulos(){
        return $this->hasMany(StockArticulo::class);
    }
}
