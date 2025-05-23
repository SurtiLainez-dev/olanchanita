<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    public function users(){
        return $this->hasManyThrough('App\Models\User','App\Models\Colaborador');
    }
}
