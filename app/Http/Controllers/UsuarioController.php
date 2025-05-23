<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Colaborador;
use App\Models\ConfiguracionImpresora;
use App\Models\DatosEmpresa;
use App\Models\Impuesto;
use App\Models\PuestoColaborador;
use App\Models\Sucursal;
use App\Models\TipoUsuario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function registroEmpresa(Request  $request){
        $nuevaEmpresa = new DatosEmpresa();
        $nuevaEmpresa->nombre_fiscal = $request->nombre_fiscal;
        $nuevaEmpresa->nombre_corto  = $request->corto;
        $nuevaEmpresa->id_fiscal     = $request->id_fiscal;
        $nuevaEmpresa->direccion     = $request->direccion;
        $nuevaEmpresa->telefono      = $request->telefono;
        $nuevaEmpresa->email         = $request->email;
        $nuevaEmpresa->save();

        $nuevaSucursal = new Sucursal();
        $nuevaSucursal->nombre = 'Sucursal Principal';
        $nuevaSucursal->abreviatura = 'SUC1';
        $nuevaSucursal->direccion_completa = $request->direccion;
        $nuevaSucursal->telefono           = $request->telefono;
        $nuevaSucursal->email              = $request->email;
        $nuevaSucursal->save();

        $nuevoPuestoColaborador = new PuestoColaborador();
        $nuevoPuestoColaborador->nombre = 'Gerente';
        $nuevoPuestoColaborador->save();

        $nuevoColaborador = new Colaborador();
        $nuevoColaborador->nombres = $request->nombres;
        $nuevoColaborador->apellidos = $request->apellidos;
        $nuevoColaborador->email     = $request->email;
        $nuevoColaborador->telefono  = $request->telefono;
        $nuevoColaborador->estado    = true;
        $nuevoColaborador->puesto_colaborador_id = $nuevoPuestoColaborador->id;
        $nuevoColaborador->sucursal_id           = $nuevaSucursal->id;
        $nuevoColaborador->identidad             = $request->identidad;
        $nuevoColaborador->save();

        $nuevoTipoUsuario = new TipoUsuario();
        $nuevoTipoUsuario->nombre = 'Admin';
        $nuevoTipoUsuario->save();

        $nuevoUsuario = new User();
        $nuevoUsuario->usuario = 'Admin';
        $nuevoUsuario->colaborador_id = $nuevoColaborador->id;
        $nuevoUsuario->email = $request->email;
        $nuevoUsuario->password = bcrypt($request->password);
        $nuevoUsuario->estado   = true;
        $nuevoUsuario->tipo_usuario_id = $nuevoTipoUsuario->id;
        $nuevoUsuario->save();

        return response()->json(['msj'=>'Se ha registrado exitosamente la empresa'],200);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])):
            $user = Auth::user();
            $col = $user->colaborador;

            if (Auth::user()->estado == 1 && $col->estado == 1):
                $col->sucursal;
                $success['token'] = $user->createToken('Tilk')->accessToken;
                $impuesto = Impuesto::activo()->first();
                $empresa  = DatosEmpresa::first();
                $impresoras = ConfiguracionImpresora::all();
                if ($user->tipo_usuario_id === 3):
                    $caja = Caja::where('user_id', $user->id)->first();
                    if ($caja):
                        return response()->json([
                            'success'    => $success,
                            'user'       => $user,
                            'impuesto'   => $impuesto->porcentaje,
                            'empresa'    => $empresa,
                            'impresoras' => $impresoras,
                            'caja'       => $caja
                        ], 200);
                    else:
                        $error = 'El usuario no tiene permitido usar la caja minimalista';
                        return response()->json($error, 422);
                    endif;
                else:
                    return response()->json([
                        'success'    => $success,
                        'user'       => $user,
                        'impuesto'   => $impuesto->porcentaje,
                        'empresa'    => $empresa,
                        'impresoras' => $impresoras,
                        'caja'       => null
                    ], 200);
                endif;
            else:
                $error = 'El usuario que se ingreso esta inactivo.';
                return response()->json($error, 422);
            endif;
        else:
            $error = 'La contraseña o correo no es el correcto.';
            return response()->json($error, 401);
        endif;
    }

    public function tiposUsuarios(){
        return response()->json(['tipos'=>TipoUsuario::all()], 200);
    }

    public function store(Request $request){
        $usuarios = User::where('email', $request->email)->get();
        foreach ($usuarios as $usuario):
            $editUsuario = User::find($usuario->id);
            $editUsuario->email = $editUsuario->email.'----';
            $editUsuario->save();
        endforeach;
        $nuevoUsuario = new User();
        $nuevoUsuario->usuario          = $request->input('usuario');
        $nuevoUsuario->colaborador_id   = $request->input('colaborador_id');
        $nuevoUsuario->email            = $request->input('email');
        $nuevoUsuario->password         = bcrypt($request->input('password'));
        $nuevoUsuario->tipo_usuario_id  = $request->input('tipo_usuario_id');
        $nuevoUsuario->estado           = true;
        $nuevoUsuario->num_ingreso      = substr(time(),0,7);
        $nuevoUsuario->save();

        return response()->json(['msj'=>'Se ha registrado el usuario exitosamente'], 200);
    }

    public function logout(Request $request){
        $user = Auth::user()->token();
        $user->revoke();
        return response()->json(['msj'=>'Se ha cerrado sesion exitosamente'],200);
    }
}
