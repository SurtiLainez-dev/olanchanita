<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    use HasFactory;

    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }

    public function item_orden_compras(){
        return $this->hasMany(ItemOrdenCompra::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
