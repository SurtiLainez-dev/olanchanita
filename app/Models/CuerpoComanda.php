<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuerpoComanda extends Model
{
    use HasFactory;
    public function articulo(){
        return $this->belongsTo(Articulo::class);
    }
    public function combo(){
        return $this->belongsTo(Combo::class);
    }
}
