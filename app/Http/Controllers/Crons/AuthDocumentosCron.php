<?php

namespace App\Http\Controllers\Crons;



use App\Models\AuthDocumento;
use Illuminate\Support\Facades\DB;

class AuthDocumentosCron
{
    public static function DeleteAuthDocumentos(){
        AuthDocumento::where('id','>', 0)->delete();
        DB::table('cajas')->update(['total'=>0]);
    }
}
