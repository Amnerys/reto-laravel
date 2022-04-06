<?php

namespace App\Http\Controllers;

use App\Helpers\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
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
                'nombre'                => 'required',
                'apellidos'             => 'required',
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
        }else{
            $data = array(
                'status' => 'error',
                'code' => '400',
                'message' => 'El usuario no está identificado'
            );
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Método para borrar un usuario
     */
    public function deleteUser($id, Request $request){
        //Buscar el registro del producto por el id
        $user = User::find($id);

        if(!empty($user)){
            //Borrar el producto
            $user->delete();
            //Devolver mensaje conforme se ha borrado
            $data = [ //Se ha podido borrar
                'code' => 200,
                'status' => 'success',
                'message' => 'Usuario eliminado correctamente.',
                'usuario' => $user
            ];
        }else{ //No se ha podido borrar
            $data = $this->statusData(400,$user);
        }
        return response()->json($data, $data['code']);
    }

    /**
     * Método para actualizar la imagen del usuario
     * Hace la autenticación del usuario a través del Middleware
     */
    public function upload(Request $request){
        //Recoger datos de la petición
        $image = $request->file('file0');

        //Validar imagen
        $validate = \Validator::make($request->all(),[
            'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);

        //Guardar imagen si no falla la validación y no está vacío
        if(!$image || $validate->fails()){
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir la imágen'
            );
        }else{
            $image_name = time().$image->getClientOriginalName(); //saca el nombre original del usuario con el time
            Storage::disk('users')->put($image_name, \File::get($image));

            $data = array(
                'image' => $image_name,
                'code' => '200',
                'message' => 'La imagen se ha subido correctamente',
                'status' => 'success'
            );
        }

        //Devolver resultado
        return response()->json($data, $data['code']);
    }
    /**
     * Método para sacar todas las imágenes asociadas al usuario
     */
    public function getImage($filename){
        //Si la imagen existe, devolverla
        $isset = Storage::disk('users')->exists($filename);
        if ($isset){
            $file = Storage::disk('users')->get($filename);
            return new Response($file, 200);
        }else{ //Si no existe, lanzar error
            $data = array(
                'code' => '404',
                'message' => 'La imagen no existe',
                'status' => 'error'
            );
            return response()->json($data, $data['code']);
        }

    }

    /**
     * Método para devolver los datos del usuario
     */
    public function detail($id){
        $user = User::find($id);

        if(is_object($user)){
            $data = array(
                'code' => '200',
                'message' => 'El usuario se ha encontrado',
                'status' => 'success',
                'user' => $user
            );
        }else{
            $data = array(
                'code' => '404',
                'message' => 'El usuario no existe',
                'status' => 'error'
            );
        }
        return response()->json($data, $data['code']);
    }
}
