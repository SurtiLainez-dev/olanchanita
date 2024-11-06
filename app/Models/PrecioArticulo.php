<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecioArticulo extends Model
{
    use HasFactory;
    public function impuesto(){
        return $this->belongsTo(Impuesto::class);
    }
    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }
}
