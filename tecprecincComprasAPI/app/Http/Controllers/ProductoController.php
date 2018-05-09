<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //cargar todos los productos
        $productos = \App\Producto::all();

        if(count($productos) == 0){
            return response()->json(['error'=>'No existen productos.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'productos'=>$productos], 200);
        } 
    }

    public function productosCategoria()
    {
        //cargar todos los productos con su categoria
        $productos = \App\Producto::with('categoria')->get();

        if(count($productos) == 0){
            return response()->json(['error'=>'No existen productos.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'productos'=>$productos], 200);
        } 
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //Crear un producto asociado a una categoria
    public function store(Request $request, $categoria_id)
    {
        // Primero comprobaremos si estamos recibiendo todos los campos.
        if ( !$request->input('nombre') )
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['error'=>'Faltan datos necesarios para el proceso de alta.'],422);
        } 

        // Comprobamos si la categoria que nos están pasando existe o no.
        $categoria = \App\Categoria::find($categoria_id);

        if(count($categoria)==0){
            return response()->json(['error'=>'No existe la categoría con id '.$categoria_id], 404);          
        } 
        
        $aux = \App\Producto::where('nombre', $request->input('nombre'))
            ->where('categoria_id', $categoria->id)->get();
        if(count($aux)!=0){
           // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Ya existe un producto con esas características en la categoria con id '.$categoria_id], 409);
        }

        //Creamos el producto asociado a la categoria
        $producto = $categoria->productos()->create($request->all());

        return response()->json(['status'=>'ok', 'message'=>'Producto creado con éxito.',
                 'producto'=>$producto], 200);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //cargar un producto
        $producto = \App\Producto::find($id);

        if(count($producto)==0){
            return response()->json(['error'=>'No existe el producto con id '.$id], 404);          
        }else{
            return response()->json(['status'=>'ok', 'producto'=>$producto], 200);
        } 
    }

    public function productoCategoria($id)
    {
        //cargar un producto con su categoria
        $producto = \App\Producto::with('categoria')->find($id);

        if(count($producto)==0){
            return response()->json(['error'=>'No existe el producto con id '.$id], 404);          
        }else{

            //cargar la servicios del categoria
            //$producto->categoria = $producto->categoria()->get();

            return response()->json(['status'=>'ok', 'producto'=>$producto], 200);
        } 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Comprobamos si el producto que nos están pasando existe o no.
        $producto = \App\Producto::find($id);

        if(count($producto)==0){
            return response()->json(['error'=>'No existe el producto con id '.$id], 404);          
        }   

        // Listado de campos recibidos teóricamente.
        $nombre=$request->input('nombre');
        $categoria_id=$request->input('categoria_id');

        // Creamos una bandera para controlar si se ha modificado algún dato.
        $bandera = false;

        // Actualización parcial de campos.
        if ($nombre != null && $nombre!='')
        {
            $aux = \App\Producto::where('nombre', $request->input('nombre'))
                ->where('id', '<>', $producto->id)->get();
            if(count($aux)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro producto con ese nombre'], 409);
            }

            $producto->nombre = $nombre;
            $bandera=true;
        }

        if ($categoria_id != null && $categoria_id !='')
         {
            // Comprobamos si la categoria que nos están pasando existe o no.
            $categoria=\App\Categoria::find($categoria_id);

            if (count($categoria)==0)
            {
                // Devolvemos error codigo http 404
                return response()->json(['error'=>'No existe la categoría con id '.$categoria_id], 404);
            }

            $producto->categoria_id = $categoria_id;
            $bandera=true;
        }

        if ($bandera)
        {
            // Almacenamos en la base de datos el registro.
            if ($producto->save()) {
                return response()->json(['status'=>'ok', 'message'=>'Producto editado con éxito.',
                        'producto'=>$producto], 200);
            }else{
                return response()->json(['error'=>'Error al actualizar el producto.'], 500);
            }
            
        }
        else
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
            // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
            return response()->json(['error'=>'No se ha modificado ningún dato al producto.'],409);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Comprobamos si el producto que nos están pasando existe o no.
        $producto = \App\Producto::find($id);

        if(count($producto)==0){
            return response()->json(['error'=>'No existe el producto con id '.$id], 404);          
        } 

        $proveedores = $producto->proveedores;

        if (sizeof($proveedores) > 0)
        {
            // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Este producto no puede ser eliminado porque posee proveedores asociados.'], 409);
        }

        // Eliminamos el producto si no tiene relaciones.
        $producto->delete();

        return response()->json(['status'=>'ok', 'message'=>'Se ha eliminado correctamente el producto.'], 200);
    }

    /*Cargar un producto con los proveedores que lo ofrecen*/
    public function productoProveedores($id)
    {
        //cargar un producto con sus proveedores 
        $producto = \App\Producto::with('proveedores')->find($id);

        if(count($producto)==0){
            return response()->json(['error'=>'No existe el producto con id '.$id], 404);          
        }else{

            //cargar la servicios del categoria
            //$producto->categoria = $producto->categoria()->get();

            return response()->json(['producto'=>$producto], 200);
        } 
    }

}
