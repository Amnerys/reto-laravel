<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function pruebas(Request $request){
        return "Acción de pruebas de Usurario";
    }

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
                $pwd = password_hash($params->password, PASSWORD_BCRYPT, ['cost'=>4]);

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

    public function login(Request $request){
        return "Acción de login de Usuario";
    }
}
