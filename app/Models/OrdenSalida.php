<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenSalida extends Model
{
    use HasFactory;

    public function cuerpo_orden_salidas(){
        return $this->hasMany(CuerpoOrdenSalida::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function sucursal(){
        return $this->belongsTo(Sucursal::class);
    }
}
