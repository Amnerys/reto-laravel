<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Constructor para que nos pida la autenticación a través del middleware excepto los mencionados
     */
    public function __construct(){
        $this->middleware('api.auth', ['except'=> [
            'index',
            'show',
            'getImage',
            'getProductsByCategory'
        ]]);
    }

    /**
     * Método para mostrar todos las productos
     */
    public function index(){
        // Usamos la relación products-categories para obtener las categorías a las que pertenecen los productos
        $products = Product::all()->load('categorias');
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'products' => $products
        ], 200);
    }

    /**
     * Método para mostrar un producto
     */
    public function show($id){
        //Sacará el producto por el id indicado y mostrará la categoría relacionada a ese producto
        $product = Product::find($id)->load('categorias');

        if(is_object($product)){
            $data = $this->statusData(200,$product); //success
        }else{
            $data = $this->statusData(404, $product); //error
        }
        // Devuelve la información con el resultado sea error o no
        return response()->json($data, $data['code']);
    }

    /**
     * Método para guardar un producto
     */
    public function store(Request $request){
        //Recoger los datos por POST
        $params_array = $this->getRequestArray($request); //array
        $params = $this->getRequestObject($request); //objeto

        //Validar los datos con Validator
        if(!empty($params_array)){
            $validate = $this->validateProduct($params_array);

            //Comprueba si falla la validación
            if($validate->fails()){
                //Algun campo de la validación falla
                $data = $this->statusData(404, $params_array);
            }else{ //Crear el producto con los datos pasados y guardarlo
                $product = new Product();
                $product->nombre_producto = $params->nombre_producto;
                $product->descripcion_producto = $params->descripcion_producto;
                $product->foto = $params->foto;
                $product->category_id = $params->category_id;
                $product->tarifa = $params->tarifa;
                $product->save();
                //Se ha podido guardar correctamente
                $data = $this->statusData(200, $params_array);
            }
        }else{
            $data = $this->statusData(400, $params_array);
        }
        //Devolver el resultado
        return response()->json($data, $data['code']);
    }

    /**
     * Método para actualizar un producto
     */
    public function update($id, Request $request){
        //Recoger datos recibidos por POST
        $params_array = $this->getRequestArray($request);

        //Si ha recogido datos en params_array
        if(!empty($params_array)){
            //Validar los datos
            $validate = $this->validateProduct($params_array);

            if ($validate->fails()){
                //Los datos no son válidos, lanzar error
                $data = $this->statusData(404,$params_array);
            }

            //Quitar datos que no queremos modificar
            unset($params_array['id']);
            unset($params_array['created_at']);

            //Actualizar producto con el id pasado
            $product = Product::where('id', $id)->updateOrCreate($params_array);

            //Se ha conseguido guardar, crear mensaje
            $data = $this->statusData(200, $product);

        }else{ //Si no hay datos, lanzar error
            $data = $this->statusData(400, $params_array);
        }
        //Devolver datos conforme se ha actualizado o ha habido error
        return response()->json($data, $data['code']);
    }

    /**
     * Método para borrar un producto
     * Se hace la autenticación por el middleware
     */
    public function destroy($id, Request $request){
        //Buscar el registro del producto por el id
        $product = Product::find($id);

        if(!empty($product)){
            //Borrar el producto
            $product->delete();
            //Devolver mensaje conforme se ha borrado
            $data = [ //Se ha podido borrar
                'code' => 200,
                'status' => 'success',
                'message' => 'Producto eliminado correctamente.',
                'product' => $product
            ];
        }else{ //No se ha podido borrar
            $data = $this->statusData(400,$product);
        }
        return response()->json($data, $data['code']);
    }

    public function upload(Request $request){
        //Recoger la imagen de la petición
        $image = $request->file('file0');
        //Validar la imágen
        $validate = \Validator::make($request->all(), [
            'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);

        //Guardar la imágen en un disco (images)
        if (!$image || $validate->fails()){ //No se puede guardar
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir la imágen'
            );
        }else{ //Se puede guardar
            $image_name = time().$image->getClientOriginalName(); //saca el nombre original del usuario con el time
            Storage::disk('images')->put($image_name, \File::get($image));

            //Datos de success
            $data = array(
                'image' => $image_name,
                'code' => '200',
                'message' => 'La imagen se ha subido correctamente',
                'status' => 'success'
            );
        }
        //Devolver datos de status
        return response()->json($data, $data['code']);
    }

    /**
     * Método para sacar todas las imágenes asociadas al producto
     */
    public function getImage($filename){
        //Si la imagen existe, devolverla
        $isset = Storage::disk('images')->exists($filename);
        if ($isset){ //Si existe (true)
            $file = Storage::disk('images')->get($filename); //conseguir la imagen con get
            return new Response($file, 200);
        }else{ //Si no existe (false), lanzar error
            $data = array(
                'code' => '404',
                'message' => 'La imagen no existe',
                'status' => 'error'
            );
            return response()->json($data, $data['code']);
        }
    }

    /**
     * Método para sacar los productos por el id de la categoría
     */
    public function getProductsByCategory($id){
        //Saca con get los productos que correspondan al id de la categoria pasado
        $products = Product::where('category_id', $id)->get();
        //Devuelve array con los datos obtenidos
        return response()->json([
            'code' => '200',
            'productos' => $products,
            'status' => 'success'
        ]);
    }

    /**
     * Método para sacar un array del request
     */
    public function getRequestArray(Request $request){
        $json = $request->input('json', null);
        return json_decode($json, true);
    }

    /**
     * Método para sacar un objeto del request
     */
    public function getRequestObject(Request $request){
        $json = $request->input('json', null);
        return json_decode($json);
    }

    /**
     * Método para validar los datos recogidos
     */
    public function validateProduct($array){
        $validation = \Validator::make($array, [
            'nombre_producto'       => 'required',
            'descripcion_producto'  => 'alpha_dash',
            //'foto'                  => 'image',
            'category_id'           => 'required|numeric',
            'tarifa'                => 'required|numeric',
        ]);
        return $validation;
    }

    /**
     * {
    "id": 1,
    "nombre_producto": "Una",
    "descripcion_producto": "",
    "foto": null,
    "category_id": 1,
    "tarifa": 0,
    "updated_at": null
    }
     *
     * Método para lanzar el mensaje correspondiente al código pasado y los datos del producto
     */
    public function statusData($code, $product){
        return match ($code) {
            200 => [ //Se ha podido actualizar
                'code' => 200,
                'status' => 'success',
                'message' => 'Producto guardado correctamente.',
                'product' => $product
            ],
            400 => [ //El usuario no ha rellenado los datos
                'code' => 400,
                'status' => 'error',
                'message' => 'Faltan datos del producto.'
            ],
            404 => [ //Alguno de los campos no cumple con las reglas de validación
                'code' => 404,
                'status' => 'error',
                'message' => 'Algún campo no es válido.'
            ],
        };
    }

}
