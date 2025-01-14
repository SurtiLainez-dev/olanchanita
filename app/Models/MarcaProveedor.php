<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarcaProveedor extends Model
{
    use HasFactory;

    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }

    public function marca(){
        return $this->belongsTo(Marca::class);
    }
}
