<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenEntrada extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function cuerpo_orden_entradas(){
        return $this->hasMany(CuerpoOrdenEntrada::class);
    }
}
