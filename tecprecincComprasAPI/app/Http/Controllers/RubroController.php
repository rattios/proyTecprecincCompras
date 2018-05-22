<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// Activamos uso de caché.
use Illuminate\Support\Facades\Cache;

class RubroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Activamos la caché de los resultados.
        //  Cache::remember('clave', $minutes, function()
        $rubros=Cache::remember('rubros',5, function()
        {
            // Caché válida durante 5 min.
            //cargar todos los rubros
            return \App\Rubro::all();
        });
        //return $rubros;
        if(count($rubros) == 0){
            return response()->json(['error'=>'No existen rubros.'], 404);          
        }else{
            return response()->json(['rubros'=>$rubros], 200);
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

                $aux1 = \App\Rubro::where('codigo', $codigo)->get();
                if(count($aux1)==0){
                   $true = false; //romper el bucle
                }
            }
            
            
        }else{
            $codigo = $request->input('codigo');

            $aux1 = \App\Rubro::where('codigo', $codigo)->get();
            if(count($aux1)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe un rubro con ese código.'], 409);
            }
        }
        
        $aux2 = \App\rubro::where('nombre', $request->input('nombre'))->get();
        if(count($aux2)!=0){
           // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Ya existe un rubro con ese nombre.'], 409);
        }

        if($nuevoRubro=\App\Rubro::create([
                'nombre'=> $request->input('nombre'),
                'codigo'=> $codigo
        ]))
        {
            if (Cache::has('rubros'))
            {
                //Borrar elemento de la cache
                Cache::forget('rubros');
            }

           return response()->json(['message'=>'Rubro creado con éxito.',
             'rubro'=>$nuevoRubro], 200);
        }else{
            return response()->json(['error'=>'Error al crear el rubro.'], 500);
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
        //cargar un rubro
        $rubro = \App\Rubro::find($id);

        if(count($rubro)==0){
            return response()->json(['error'=>'No existe el rubro con id '.$id], 404);          
        }else{
            return response()->json(['rubro'=>$rubro], 200);
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
        // Comprobamos si el rubro que nos están pasando existe o no.
        $rubro=\App\Rubro::find($id);

        if (count($rubro)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el rubro con id '.$id], 404);
        }      

        // Listado de campos recibidos teóricamente.
        $nombre=$request->input('nombre');
        $codigo=$request->input('codigo');


        // Creamos una bandera para controlar si se ha modificado algún dato.
        $bandera = false;

        // Actualización parcial de campos.
        if ($nombre != null && $nombre!='')
        {
            $aux = \App\Rubro::where('nombre', $request->input('nombre'))
            ->where('id', '<>', $id)->get();

            if(count($aux)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro rubro con ese nombre.'], 409);
            }

            $rubro->nombre = $nombre;
            $bandera=true;
        }

        if ($codigo != null && $codigo!='')
        {
            $aux = \App\rubro::where('codigo', $request->input('codigo'))
            ->where('id', '<>', $id)->get();

            if(count($aux)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro rubro con ese código.'], 409);
            }

            $rubro->codigo = $codigo;
            $bandera=true;
        }

        if ($bandera)
        {
            // Almacenamos en la base de datos el registro.
            if ($rubro->save()) {

                if (Cache::has('rubros'))
                {
                    //Borrar elemento de la cache
                    Cache::forget('rubros');
                }

                return response()->json(['message'=>'Rubro editado con éxito.',
                    'rubro'=>$rubro], 200);
            }else{
                return response()->json(['error'=>'Error al actualizar el rubro.'], 500);
            }
            
        }
        else
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
            // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
            return response()->json(['error'=>'No se ha modificado ningún dato al rubro.'],409);
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
        // Comprobamos si el rubro existe o no.
        $rubro=\App\Rubro::find($id);

        if (count($rubro)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el rubro con id '.$id], 404);
        }
       
        $categorias = $rubro->categorias;

        if (sizeof($categorias) > 0)
        {
            // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Este rubro no puede ser eliminado porque posee categorías asociadas.'], 409);
        }

        // Eliminamos el rubro si no tiene relaciones.
        $rubro->delete();

        if (Cache::has('rubros'))
        {
            //Borrar elemento de la cache
            Cache::forget('rubros');
        }

        return response()->json(['message'=>'Se ha eliminado correctamente el rubro.'], 200);
    }
}
