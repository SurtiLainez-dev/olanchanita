<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    public function articulos(){
        return $this->hasManyThrough('App\Models\Articulo','App\Models\Marca');
    }

    public function marca_proveedors(){
        return $this->hasMany(MarcaProveedor::class);
    }

}
