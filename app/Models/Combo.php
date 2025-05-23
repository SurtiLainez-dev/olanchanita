<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    use HasFactory;

    protected $hidden = [
        'articulos',
    ];

    public function cuerpo_combos(){
        return $this->hasMany(CuerpoCombo::class);
    }
}
