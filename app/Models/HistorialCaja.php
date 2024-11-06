<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialCaja extends Model
{
    use HasFactory;

    public function factura(){
        return $this->belongsTo(Factura::class);
    }

    public function caja(){
        return $this->belongsTo(Caja::class);
    }

    public function forma_pago(){
        return $this->belongsTo(FormaPago::class);
    }

    public function retirada_efectivo(){
        return $this->belongsTo(RetiradaEfectivo::class);
    }

    public function recibo(){
        return $this->belongsTo(Recibo::class);
    }

}
