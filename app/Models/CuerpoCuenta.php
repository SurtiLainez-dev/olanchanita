<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuerpoCuenta extends Model
{
    use HasFactory;

    public function factura(){
        return $this->belongsTo(Factura::class);
    }
}
