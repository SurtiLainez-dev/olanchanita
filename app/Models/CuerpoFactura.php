<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuerpoFactura extends Model
{
    use HasFactory;

    public function articulo(){
        return $this->belongsTo(Articulo::class);
    }

    public function combo(){
        return $this->belongsTo(Combo::class);
    }

    public function impuesto(){
        return $this->belongsTo(Impuesto::class);
    }
}
