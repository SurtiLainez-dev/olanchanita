<?php

namespace App\Http\Controllers;

use App\Models\AuthDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthDocumentosController extends Controller
{
    public function solicitud(Request $request){
        $nuevaClave = $this->ejecutarClave();

        return response()->json(['clave'=>$nuevaClave->token],200);
    }

    public static function ejecutarClave(){
        $nuevaClave             = new AuthDocumento();
        $nuevaClave->token      = self::clave(60);
        $nuevaClave->user_id    = Auth::user()->id;
        $nuevaClave->expiracion = now("America/New_York")->addMinute(10);
        $nuevaClave->save();

        return $nuevaClave;
    }

    public static function clave($length){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_<>';
        $charactersLength = strlen($characters);
        $randomString = '';
        $bandera          = true;
        while ($bandera):
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            if (AuthDocumento::where('token', $randomString)->exists()):
                $bandera = true;
            else:
                $bandera = false;
            endif;
        endwhile;
        return $randomString;
    }
}
