<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaBancoColaborador extends Model
{
    use HasFactory;

    public function banco(){
        return $this->belongsTo(Banco::class);
    }
}
