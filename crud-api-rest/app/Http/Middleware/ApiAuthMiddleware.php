<?php

namespace App\Http\Middleware;

use App\Helpers\JWTAuth;
use Closure;
use Illuminate\Http\Request;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //Comprobar si el usuario está identificado
        $token = $request->header('Authorization'); //coger de la cabecera la autenticación
        $jwtAuth = new JWTAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if($checkToken){
            return $next($request);
        } else{
            //devolver mensaje de error
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'El usuario no está identificado'
            );
            return response()->json($data, $data['code']);
        }
    }
}
