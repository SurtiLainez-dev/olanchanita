<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }

    public function marca_proveedor(){
        return $this->hasMany(MarcaProveedor::class);
    }

    public function articulos(){
        return $this->hasMany(Articulo::class);
    }
}
