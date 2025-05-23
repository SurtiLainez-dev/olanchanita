<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    use HasFactory;

    public function cuerpo_cuentas(){
        return $this->hasMany(CuerpoCuenta::class);
    }
}
