<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comanda extends Model
{
    use HasFactory;

    public function cuerpo_comandas(){
        return $this->hasMany(CuerpoComanda::class);
    }

    public function mesa(){
        return $this->belongsTo(Mesa::class);
    }
}
