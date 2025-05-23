<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuerpoCombo extends Model
{
    use HasFactory;
    public function articulo(){
        return $this->belongsTo(Articulo::class);
    }
}
