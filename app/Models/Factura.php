<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    public function cuerpo_facturas(){
        return $this->hasMany(CuerpoFactura::class);
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }

    public function forma_pago(){
        return $this->belongsTo(FormaPago::class);
    }

    public function directriz_impresion(){
        return $this->belongsTo(DirectrizImpresion::class);
    }

    public function historial_caja(){
        return $this->hasOne(HistorialCaja::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
