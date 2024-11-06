<?php

namespace App\Http\Middleware;

use App\Models\AuthDocumento;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CheckClaveDocumento
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $user = User::firstWhere('usuario', $request->user);
        if ($user):
            $clave = AuthDocumento::where([['token','=', $request->clave],['user_id','=',$user->id]])->first();
            if (!empty($clave) && now() <= $clave->expiracion)
                return $next($request);
            else
                return abort(403);
        else:
            return abort(401);
        endif;
    }
}
