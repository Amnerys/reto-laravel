<?php

namespace App\Http\Controllers;

use App\Helpers\JWTAuth;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function pruebas(Request $request){
        return "Acción de pruebas de Usurario";
    }

    /**
     * Método para hacer el registro de usuarios
     */
    public function register(Request $request){
        //Recoger los datos del usuario por post
        $json = $request->input('json',null);
        $params = json_decode($json); //objeto
        $params_array = json_decode($json,true); //array

        //Si encuentra datos en el objeto y el array, continuar
        if(!empty($params) && !empty($params_array)){

            //Limpiar datos obtenidos
            $params_array = array_map('trim', $params_array);

            //Regla de validación para los datos recogidos
            $validate = \Validator::make($params_array, [
                'nombre'                => 'required|alpha',
                'apellidos'             => 'required|alpha',
                'fecha_nacimiento'      => 'date',
                'email'                 => 'required|email|unique:users',
                'password'              => 'required',
                'foto'                  => 'file'
            ]);

            //Si la validación falla:
            if($validate->fails()){
                $data = array(
                    'status' => 'error',
                    'code' => '404',
                    'message' => 'El usuario no se ha podido crear',
                    'errors' => $validate->errors()
                );
            }else{ //Si la validación es CORRECTA:
                //Cifrar la contraseña 4 veces
                $pwd = hash('sha256', $params->password);

                //Crear el usuario y guardarlo en la base de datos
                $user = new User();
                $user->nombre = $params_array['nombre'];
                $user->apellidos = $params_array['apellidos'];
                $user->fecha_nacimiento = $params_array['fecha_nacimiento'];
                $user->email = $params_array['email'];
                $user->password = $pwd;
                $user->foto = $params_array['foto'];
                $user->save();

                $data = array(
                    'status' => 'success',
                    'code' => '200',
                    'message' => 'El usuario se ha creado correctamente',
                    'user' => $user
                );
            }
        }else{ //Si los datos introducidos no son correctos
            $data = array(
                'status' => 'success',
                'code' => '404',
                'message' => 'Los datos enviados no son válidos'
            );
        }

        //Devuelve la respuesta en función si ha validado correctamente o no el usuario
        return response()->json($data, $data['code']);
    }

    /**
     * Método para hacer el login de usuarios
     */
    public function login(Request $request){

        $jwtAuth = new JWTAuth();

        //Recibir los datos por POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true); //para hacer la validación

        //Validar los datos recibidos
        $validate = \Validator::make($params_array, [
            'email'                 => 'required|email',
            'password'              => 'required'
        ]);

        if($validate->fails()) { //Si la validación falla, devolvernos el mensaje de error
            $signUp = array(
                'status' => 'error',
                'code' => '404',
                'message' => 'El usuario no se ha podido identificar correctamente',
                'errors' => $validate->errors()
            );
        } else{
            //Cifrar la contraseña
            $pwd = hash('sha256', $params->password);
            //Devolver token / datos
            $signUp = $jwtAuth->signUp($params->email, $pwd);
            if(isset($params->gettoken)){
                $signUp = $jwtAuth->signUp($params->email, $pwd, true);
            }
        }

        return response()->json($signUp);
    }

    /**
     * Método para actualizar los datos del usuario
     */
    public function update(Request $request){
        //Comprobar si el usuario está identificado
        $token = $request->header('Authorization'); //coger de la cabecera la autenticación
        $jwtAuth = new JWTAuth();
        $checkToken = $jwtAuth->checkToken($token);

        //Recoger los datos con POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if($checkToken && !empty($params_array)){

            $user = $jwtAuth->checkToken($token, true);// sacar datos de usuario identificado

            //Validar datos obtenidos
            $validate = \Validator::make($params_array, [
                'nombre'                => 'required|alpha',
                'apellidos'             => 'required|alpha',
                'fecha_nacimiento'      => 'date',
                'email'                 => 'required|email|unique:users'.$user->sub, //excepto el email asignado al usuario
                'foto'                  => 'file'
            ]);

            //Quitar los campos que no deseo actualizar
            unset($params_array['id']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['remember_token']);

            //Actualizar usuario en la BD
            $user_update = User::where('id', $user->sub)->update($params_array);

            //Devolver array con el resultado
            $data = array(
                'status' => 'success',
                'code' => '200',
                'message' => 'El usuario se ha actualizado correctamente',
                'user' => $user,
                'changes' => $params_array
            );

        }else{ //devolver mensaje de error
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'El usuario no está identificado'
            );
        }
        return response()->json($data, $data['code']);
    }

    /**
     * Método para actualizar la imagen del usuario
     */
    public function upload(Request $request){

        $data = array(
            'code' => 400,
            'status' => 'error',
            'message' => 'El usuario no está identificado'
        );
        return response()->json($data, $data['code']);
    }
}
