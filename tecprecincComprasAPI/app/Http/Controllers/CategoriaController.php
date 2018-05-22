<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// Activamos uso de caché.
use Illuminate\Support\Facades\Cache;

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
        //$categorias = \App\Categoria::all();

        // Activamos la caché de los resultados.
        //  Cache::remember('clave', $minutes, function()
        $categorias=Cache::remember('categorias',5, function()
        {
            // Caché válida durante 5 min.
            //cargar todos las categorias
            return \App\Categoria::with('tipo')->with('rubro')->get();
        });
        //return $categorias; 
        if(count($categorias) == 0){
            return response()->json(['error'=>'No existen categorías.'], 404);          
        }else{
            return response()->json(['categorias'=>$categorias], 200);
        } 
        
    }

    public function indexfull()
    {
        $tipos = \App\Tipo::all();
        $rubros = \App\Rubro::all();
        $categorias = \App\Categoria::with('tipo')->with('rubro')->get();
        
        if(count($categorias) == 0){
            return response()->json(['error'=>'No existen categorías.'], 404);          
        }else{
            return response()->json(
                [
                    'tipos'=>$tipos,
                    'rubros'=>$rubros,
                    'categorias'=>$categorias
                ], 200);
        } 
        
    }

    public function categoriasProductos()
    {
        //cargar todas las cat con sus productos (productos en general)
        $categorias = \App\Categoria::with('tipo')->with('rubro')->with('productos')->get();

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
        if ( !$request->input('nombre'))
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['error'=>'Faltan datos necesarios para el proceso de alta.'],422);
        } 

        if ( !$request->input('codigo'))
        {
            //Generar código alatorio
            $salt = '1234567890';

            $true = true;
            while ($true) {
                $rand = '';
                $i = 0;
                $length = 4;

                while ($i < $length) {
                    //Loop hasta que el string aleatorio contenga la longitud ingresada.
                    $num = rand() % strlen($salt);
                    $tmp = substr($salt, $num, 1);
                    $rand = $rand . $tmp;
                    $i++;
                }

                $codigo = $rand;

                $aux1 = \App\Categoria::where('codigo', $codigo)->get();
                if(count($aux1)==0){
                   $true = false; //romper el bucle
                }
            }
            
            
        }else{
            $codigo = $request->input('codigo');

            $aux1 = \App\Categoria::where('codigo', $codigo)->get();
            if(count($aux1)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe una categoría con ese código.'], 409);
            }
        }
        
        $aux2 = \App\Categoria::where('nombre', $request->input('nombre'))
            ->orWhere('codigo', $request->input('codigo'))->get();
        if(count($aux2)!=0){
           // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Ya existe una categoría con esas características.'], 409);
        }

        if($request->input('tipo_id')){
            $tipo = \App\Tipo::find($request->input('tipo_id'));

            if(count($tipo)==0){
                return response()->json(['error'=>'No existe el tipo con id '.$request->input('tipo_id')], 404);          
            }
        }

        if($request->input('rubro_id')){
            $rubro = \App\Rubro::find($request->input('rubro_id'));

            if(count($rubro)==0){
                return response()->json(['error'=>'No existe el rubro con id '.$request->input('rubro_id')], 404);          
            }
        }

        if($nuevaCategoria=\App\Categoria::create([
                'nombre'=> $request->input('nombre'),
                'codigo'=> $codigo,
                'tipo_id'=> $request->input('tipo_id'),
                'rubro_id'=> $request->input('rubro_id')
        ]))
        {

            if (Cache::has('categorias'))
            {
                //Borrar elemento de la cache
                Cache::forget('categorias');
            }

           return response()->json(['message'=>'Categoría creada con éxito.',
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
        $categoria = \App\Categoria::with('tipo')->with('rubro')->find($id);

        if(count($categoria)==0){
            return response()->json(['error'=>'No existe la categoría con id '.$id], 404);          
        }else{
            return response()->json(['categoria'=>$categoria], 200);
        } 
    }

    public function categoriaProductos($id)
    {

        //cargar una cat con sus subcat
        $categoria = \App\Categoria::with('tipo')->with('rubro')->with('productos')->find($id);

        if(count($categoria)==0){
            return response()->json(['error'=>'No existe la categoría con id '.$id], 404);          
        }else{

            //cargar las subcat de la cat
            //$categoria = $categoria->with('subcategorias')->get();
            //$categoria->productos = $categoria->productos()->get();
            //$categoria = $categoria->subcategorias;

            return response()->json(['categoria'=>$categoria], 200);
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
    public function update(Request $request)
    {
        // Primero comprobaremos si estamos recibiendo todos los campos.

        if ( !$request->input('categorias'))
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['error'=>'Falta el arreglo de categoría(s) a editar.'],422);
        }

        $categorias = json_decode($request->input('categorias'));
        //$categorias = json_decode('[{"categoria_id":192,"rubro_id":3,"tipo_id":3}]')
        //$categorias = json_decode('[{ "name":"John", "age":30, "car":null }]');
        //$categorias = $request->input('categorias');
        //return $categorias;
        for ($i=0; $i < count($categorias) ; $i++) { 

            // Comprobamos si la categoria que nos están pasando existe o no.
            $categoria=\App\Categoria::find($categorias[$i]->categoria_id);

            if (!$categoria)
            {
                // Devolvemos error codigo http 404
                return response()->json(['error'=>'No existe la categoría con id '.$categorias[$i]->categoria_id], 404);
            }      
            //
            // Listado de campos recibidos teóricamente.
            /*if (property_exists($categorias[$i], 'nombre')) {
                $nombre = $categorias[$i]->nombre;
            }else{
                $nombre = null;
            }

            if (property_exists($categorias[$i], 'codigo')) {
                $codigo = $categorias[$i]->codigo;
            }else{
                $codigo = null;
            }*/

            if (property_exists($categorias[$i], 'tipo_id')) {
                $tipo_id = $categorias[$i]->tipo_id;
            }else{
                $tipo_id = null;
            }

            if (property_exists($categorias[$i], 'rubro_id')) {
                $rubro_id = $categorias[$i]->rubro_id;
            }else{
                $rubro_id = null;
            }


            // Creamos una bandera para controlar si se ha modificado algún dato.
            $bandera = false;

            // Actualización parcial de campos.
            /*if ($nombre != null && $nombre!='')
            {
                $aux = \App\Categoria::where('nombre', $nombre)
                ->where('id', '<>', $categoria->id)->get();

                if(count($aux)!=0){
                   // Devolvemos un código 409 Conflict. 
                    return response()->json(['error'=>'Ya existe otra categoría con el nombre '.$nombre.'.'], 409);
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
                    return response()->json(['error'=>'Ya existe otra categoría con el codigo '.$codigo.'.'], 409);
                }

                $categoria->codigo = $codigo;
                $bandera=true;
            }*/

            if ($tipo_id != null && $tipo_id!='')
            {
                $aux = \App\Tipo::find($tipo_id);
                
                if(!$aux){
                   // Devolvemos un código 409 Conflict. 
                    return response()->json(['error'=>'No existe el tipo con id '.$tipo_id.'.'], 409);
                }

                $categoria->tipo_id = $tipo_id;
                $bandera=true;
            }

            if ($rubro_id != null && $rubro_id!='')
            {
                $aux = \App\Rubro::find($rubro_id);
                
                if(!$aux){
                   // Devolvemos un código 409 Conflict. 
                    return response()->json(['error'=>'No existe el rubro con id '.$rubro_id.'.'], 409);
                }

                $categoria->rubro_id = $rubro_id;
                $bandera=true;
            }

            if ($bandera)
            {
                // Almacenamos en la base de datos el registro.
                if ($categoria->save()) {
                    
                    //continue;
                    //return response()->json(['message'=>'Categoría(s) editada(s) con éxito.'], 200);
                    
                }else{
                    return response()->json(['error'=>'Error al actualizar la categoría con id '.$categoria->id], 500);
                }
                
            }
            
        } 
        return $categoria;
         /*if ($bandera) {
            if (Cache::has('categorias'))
            {
                //Borrar elemento de la cache
                Cache::forget('categorias');
            }
        }*/

        
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

        if (Cache::has('categorias'))
        {
            //Borrar elemento de la cache
            Cache::forget('categorias');
        }

        return response()->json(['message'=>'Se ha eliminado correctamente la categoría.'], 200);
    }
}
