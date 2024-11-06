<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    use HasFactory;

    public function factura(){
        return $this->belongsTo(Factura::class);
    }

    public function caja(){
        return $this->belongsTo(Caja::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function cuenta(){
        return $this->belongsTo(Cuenta::class);
    }
}
