<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Constructor para que nos pida la autenticación a través del middleware
     */
    public function __construct(){
        $this->middleware('api.auth', ['except'=> ['index','show']]);
    }

    /**
     * Método para mostrar todas las categorías
     */
    public function index(){
        $categories = Category::all();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'categories' => $categories
        ]);
    }

    /**
     * Método para mostrar una categoría
     */
    public function show($id){
        $category = Category::find($id);

        if(is_object($category)){
            $data = [
                'code' => 200,
                'status' => 'success',
                'categories' => $category
            ];
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'La categoría no existe'
            ];
        }
        return response()->json($data, $data['code']);
    }

    /**
     * Método para guardar una categoría
     */
    public function store(Request $request){
        //Recoger los datos por POST y validarlos con Validator
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if(!empty($params_array)){
            $validate = \Validator::make($params_array, [
                'nombre_categoria' => 'required'
            ]);

            //Guardar la categoría si no falla la validación
            if($validate->fails()){
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'No se ha guardado la categoría'
                ];
            }else{
                $category = new Category();
                $category->nombre_categoria = $params_array['nombre_categoria'];
                $category->descripcion_categoria = $params_array['descripcion_categoria'];
                $category->save();

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Categoría guardada.',
                    'category' => $category
                ];
            }
        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'No has escrito ninguna categoría válida.'
            ];
        }

        //Devolver el resultado
        return response()->json($data, $data['code']);
    }

    /**
     * Método para actualizar una categoría
     */
    public function update($id, Request $request){
        //Recoger datos recibidos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        //Validar los datos
        if(!empty($params_array)){
            $validate = \Validator::make($params_array, [
                'nombre_categoria' => 'alpha_dash|required',
                'descripcion_categoria' => 'alpha_dash'
            ]);

            //Quitar campos que no deseo actualizar
            unset($params_array['id']);
            unset($params_array['created_at']);

            //Actualizar la categoría
            $category_update = Category::where('id', $id)->update($params_array);
            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Categoría guardada.',
                'category' => $params_array
            ];

        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'No has escrito ninguna categoría válida.'
            ];
        }

        //Devolver los datos
        return response()->json($data, $data['code']);
    }

    /**
     * Método para borrar una categoría
     * Se hace la autenticación por el middleware
     */
    public function destroy($id, Request $request){
        //Buscar el registro del producto por el id
        $category = Category::find($id);

        if(!empty($category)){
            //Borrar el producto
            $category->delete();
            //Devolver mensaje conforme se ha borrado
            $data = [ //Se ha podido borrar
                'code' => 200,
                'status' => 'success',
                'message' => 'Categoría eliminada correctamente.',
                'product' => $category
            ];
        }else{ //No se ha podido borrar
            $data = $this->statusData(400,$category);
        }
        return response()->json($data, $data['code']);
    }
}
