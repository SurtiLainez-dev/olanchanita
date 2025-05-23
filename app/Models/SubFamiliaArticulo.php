<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubFamiliaArticulo extends Model
{
    use HasFactory;
    public function familia_articulo(){
        return $this->belongsTo(FamiliaArticulo::class);
    }
}
