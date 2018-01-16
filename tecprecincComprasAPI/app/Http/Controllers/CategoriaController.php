<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //cargar todas las cat
        $categorias = \App\Categoria::all();

        if(count($categorias) == 0){
            return response()->json(['error'=>'No existen categorías.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'categorias'=>$categorias], 200);
        } 
        
    }

    public function categoriasProductos()
    {
        //cargar todas las cat con sus productos (productos en general)
        $categorias = \App\Categoria::with('productos')->get();

        if(count($categorias) == 0){
            return response()->json(['error'=>'No existen categorías.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'categorias'=>$categorias], 200);
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
    public function store(Request $request)
    {
        // Primero comprobaremos si estamos recibiendo todos los campos.
        if ( !$request->input('nombre') || !$request->input('codigo'))
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['error'=>'Faltan datos necesarios para el proceso de alta.'],422);
        } 
        
        $aux = \App\Categoria::where('nombre', $request->input('nombre'))
            ->orWhere('codigo', $request->input('codigo'))->get();
        if(count($aux)!=0){
           // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Ya existe una categoría con esas características.'], 409);
        }

        if($nuevaCategoria=\App\Categoria::create($request->all())){
           return response()->json(['status'=>'ok','message'=>'Categoría creada con éxito.',
             'categoria'=>$nuevaCategoria], 200);
        }else{
            return response()->json(['error'=>'Error al crear la categoría.'], 500);
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //cargar una cat
        $categoria = \App\Categoria::find($id);

        if(count($categoria)==0){
            return response()->json(['error'=>'No existe la categoría con id '.$id], 404);          
        }else{
            return response()->json(['status'=>'ok', 'categoria'=>$categoria], 200);
        } 
    }

    public function categoriaProductos($id)
    {

        //cargar una cat con sus subcat
        $categoria = \App\Categoria::with('productos')->find($id);

        if(count($categoria)==0){
            return response()->json(['error'=>'No existe la categoría con id '.$id], 404);          
        }else{

            //cargar las subcat de la cat
            //$categoria = $categoria->with('subcategorias')->get();
            //$categoria->productos = $categoria->productos()->get();
            //$categoria = $categoria->subcategorias;

            return response()->json(['status'=>'ok', 'categoria'=>$categoria], 200);
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
        // Comprobamos si la categoria que nos están pasando existe o no.
        $categoria=\App\Categoria::find($id);

        if (count($categoria)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe la categoría con id '.$id], 404);
        }      

        // Listado de campos recibidos teóricamente.
        $nombre=$request->input('nombre');
        $codigo=$request->input('codigo');


        // Creamos una bandera para controlar si se ha modificado algún dato.
        $bandera = false;

        // Actualización parcial de campos.
        if ($nombre != null && $nombre!='')
        {
            $aux = \App\Categoria::where('nombre', $request->input('nombre'))
            ->where('id', '<>', $categoria->id)->get();

            if(count($aux)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otra categoría con ese nombre.'], 409);
            }

            $categoria->nombre = $nombre;
            $bandera=true;
        }

        if ($codigo != null && $codigo!='')
        {
            $aux = \App\Categoria::where('codigo', $request->input('codigo'))
            ->where('id', '<>', $categoria->id)->get();

            if(count($aux)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otra categoría con ese codigo.'], 409);
            }

            $categoria->codigo = $codigo;
            $bandera=true;
        }

        if ($bandera)
        {
            // Almacenamos en la base de datos el registro.
            if ($categoria->save()) {
                return response()->json(['status'=>'ok', 'message'=>'Categoría editada con éxito.',
                    'categoria'=>$categoria], 200);
            }else{
                return response()->json(['error'=>'Error al actualizar la categoría.'], 500);
            }
            
        }
        else
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
            // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
            return response()->json(['error'=>'No se ha modificado ningún dato la categoría.'],409);
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
        // Comprobamos si la categoria existe o no.
        $categoria=\App\Categoria::find($id);

        if (count($categoria)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe la categoría con id '.$id], 404);
        }
       
        $productos = $categoria->productos;
        $stockDepartamentos = $categoria->stockDepartamentos;
        $stock = $categoria->stock;

        if (sizeof($productos) > 0 || sizeof($stockDepartamentos) > 0 || sizeof($stock) > 0)
        {
            // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Esta categoría no puede ser eliminada porque posee productos asociados.'], 409);
        }

        // Eliminamos la categoria si no tiene relaciones.
        $categoria->delete();

        return response()->json(['status'=>'ok', 'message'=>'Se ha eliminado correctamente la categoría.'], 200);
    }
}
