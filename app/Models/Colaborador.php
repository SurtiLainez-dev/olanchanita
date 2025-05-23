<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    use HasFactory;
    public function sucursal(){
        return $this->belongsTo(Sucursal::class);
    }

    public function puesto_colaborador(){
        return $this->belongsTo(PuestoColaborador::class);
    }

    public function contratos(){
        return $this->hasMany(Contrato::class);
    }

    public function user(){
        return $this->hasOne(User::class);
    }

    public function cuentas_banco_colaboradors(){
        return $this->hasMany(CuentaBancoColaborador::class);
    }
}
