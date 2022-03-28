<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JWTAuth{

    public $key;

    public function __construct()
    {
        $this->key = 'clave_secreta_1234';
    }

    /**
     * Método para hacer el sign Up del usuario a través del email y la password proporcionada
     */
    public function signUp($email,$password, $getToken=null){

        //Buscar si existe el usuario con las credenciales proporcionadas (email y contraseña)
        $user = User::where([
            'email' => $email,
            'password' => $password
        ])->first();

        //Comprobar si nos devuelve un objeto (correcto)
        $signUp = false; //por defecto, si no se valida
        if(is_object($user)){
            $signUp = true; //los datos del usuario son correctos si es un objeto
        }

        //Generar el token con los datos del usuario identificado
        if($signUp){
            $token = array(
                'sub' => $user->id,
                'email' => $user->email,
                'nombre' => $user->nombre,
                'apellidos' =>  $user->apellidos,
                'fecha_nacimiento' => $user->fecha_nacimiento,
                'foto' => $user->foto,
                'iat' => time(), //cuándo se creó el token
                'exp' => time() + (7*24*60*60) //expirá en una semana el token
            );
            $jwt = JWT::encode($token, $this->key);
            $decoded = JWT::decode($jwt, $this->key, array_keys(JWT::$supported_algs));

            //Devolver los datos decodificados o el token
            if(is_null($getToken)){
                $data = $jwt;
            } else{
                $data = $decoded;
            }

        }else{
            $data = array(
                'status' => 'error',
                'message' => 'Login incorrecto'
            );
        }

        return $data;
    }

    /**
     * Comprueba que el token recibido sea correcto
     */
    public function checkToken($jwt, $getIdentity=false){
        $auth = false; //autenticación inicializada a falsa por defecto

        try{ //Recoger posibles excepciones
            $jwt = str_replace('"','', $jwt); //quitar comillas del token
            $decoded = JWT::decode($jwt, $this->key, array_keys(JWT::$supported_algs));
        }catch (\UnexpectedValueException $e){
            $auth = false;
        }catch (\DomainException $e){
            $auth = false;
        }

        //Comprobar que decoded sea un objeto y si existe el identificador del usuario dentro del token
        if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)){
            $auth = true;
        }else{
            $auth = false;
        }

        if($getIdentity){
            return $decoded;
        }

        return $auth;
    }
}
