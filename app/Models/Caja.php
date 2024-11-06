<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;

    public function sucursal(){
        return $this->belongsTo(Sucursal::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function historial_cajas(){
        return $this->hasMany(HistorialCaja::class);
    }
}
