<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// Activamos uso de caché.
use Illuminate\Support\Facades\Cache;

class TipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //cargar todos los tipos
        //$tipos = \App\Tipo::all();

        // Activamos la caché de los resultados.
        //  Cache::remember('clave', $minutes, function()
        $tipos=Cache::remember('tipos',5, function()
        {
            // Caché válida durante 5 min.
            //cargar todos los tipos
            return \App\Tipo::all();
        });
        //return $tipos;
        if(count($tipos) == 0){
            return response()->json(['error'=>'No existen tipos.'], 404);          
        }else{
            return response()->json(['tipos'=>$tipos], 200);
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

                $aux1 = \App\Tipo::where('codigo', $codigo)->get();
                if(count($aux1)==0){
                   $true = false; //romper el bucle
                }
            }
            
            
        }else{
            $codigo = $request->input('codigo');

            $aux1 = \App\Tipo::where('codigo', $codigo)->get();
            if(count($aux1)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe un tipo con ese código.'], 409);
            }
        }
        
        $aux2 = \App\Tipo::where('nombre', $request->input('nombre'))->get();
        if(count($aux2)!=0){
           // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Ya existe un tipo con ese nombre.'], 409);
        }

        if($nuevoTipo=\App\Tipo::create([
                'nombre'=> $request->input('nombre'),
                'codigo'=> $codigo
        ]))
        {
            if (Cache::has('tipos'))
            {
                //Borrar elemento de la cache
                Cache::forget('tipos');
            }

           return response()->json(['message'=>'Tipo creado con éxito.',
             'tipo'=>$nuevoTipo], 200);
        }else{
            return response()->json(['error'=>'Error al crear el tipo.'], 500);
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
        //cargar un tipo
        $tipo = \App\Tipo::find($id);

        if(count($tipo)==0){
            return response()->json(['error'=>'No existe el tipo con id '.$id], 404);          
        }else{
            return response()->json(['tipo'=>$tipo], 200);
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
        // Comprobamos si el tipo que nos están pasando existe o no.
        $tipo=\App\Tipo::find($id);

        if (count($tipo)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el tipo con id '.$id], 404);
        }      

        // Listado de campos recibidos teóricamente.
        $nombre=$request->input('nombre');
        $codigo=$request->input('codigo');


        // Creamos una bandera para controlar si se ha modificado algún dato.
        $bandera = false;

        // Actualización parcial de campos.
        if ($nombre != null && $nombre!='')
        {
            $aux = \App\Tipo::where('nombre', $request->input('nombre'))
            ->where('id', '<>', $id)->get();

            if(count($aux)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro tipo con ese nombre.'], 409);
            }

            $tipo->nombre = $nombre;
            $bandera=true;
        }

        if ($codigo != null && $codigo!='')
        {
            $aux = \App\Tipo::where('codigo', $request->input('codigo'))
            ->where('id', '<>', $id)->get();

            if(count($aux)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro tipo con ese código.'], 409);
            }

            $tipo->codigo = $codigo;
            $bandera=true;
        }

        if ($bandera)
        {
            // Almacenamos en la base de datos el registro.
            if ($tipo->save()) {

                if (Cache::has('tipos'))
                {
                    //Borrar elemento de la cache
                    Cache::forget('tipos');
                }

                return response()->json(['message'=>'tipo editado con éxito.',
                    'tipo'=>$tipo], 200);
            }else{
                return response()->json(['error'=>'Error al actualizar el tipo.'], 500);
            }
            
        }
        else
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
            // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
            return response()->json(['error'=>'No se ha modificado ningún dato al tipo.'],409);
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
        // Comprobamos si el tipo existe o no.
        $tipo=\App\tipo::find($id);

        if (count($tipo)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el tipo con id '.$id], 404);
        }
       
        $categorias = $tipo->categorias;

        if (sizeof($categorias) > 0)
        {
            // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Este tipo no puede ser eliminado porque posee categorías asociadas.'], 409);
        }

        // Eliminamos el tipo si no tiene relaciones.
        $tipo->delete();

        if (Cache::has('tipos'))
        {
            //Borrar elemento de la cache
            Cache::forget('tipos');
        }

        return response()->json(['message'=>'Se ha eliminado correctamente el tipo.'], 200);
    }
}
