<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamiliaArticulo extends Model
{
    use HasFactory;
    public function sub_familia_articulos(){
        return $this->hasMany(SubFamiliaArticulo::class);
    }
}
