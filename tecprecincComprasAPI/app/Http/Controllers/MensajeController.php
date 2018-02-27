<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class MensajeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //cargar todos los mensajes
        $mensajes = \App\Mensaje::with('departamento')->get();

        if(count($mensajes) == 0){
            return response()->json(['error'=>'No existen mensajes.'], 404);          
        }else{
            return response()->json(['mensajes'=>$mensajes], 200);
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
        if ( !$request->input('tipo') ||
            !$request->input('asunto') ||
            !$request->input('departamento_id') 
        )
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['error'=>'Faltan datos necesarios para el proceso de alta.'],422);
        } 

        // Comprobamos si el departamento_id que nos están pasando existe o no.
        $departamento = \App\Departamento::find($request->input('departamento_id'));
        if(count($departamento)==0){
            return response()->json(['error'=>'No existe el departamento con id '.$request->input('departamento_id')], 404);          
        }
 
        if($nuevoMsg=\App\Mensaje::create([
            'estado'=> 1,
            'tipo'=> $request->input('tipo'),
            'asunto'=> $request->input('asunto'),
            'msg'=> $request->input('msg'),
            'adjunto'=> $request->input('adjunto'),
            'departamento_id'=> $request->input('departamento_id')
        ]))
        {
            /*Aqui crear el msg para informar al departamento que tiene una nueva transferencia pendiente*/

           return response()->json(['message'=>'Mensaje creado con éxito.',
             'mensaje'=>$nuevoMsg], 200);
        }else{
            return response()->json(['error'=>'Error al crear el mensaje.'], 500);
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
        //cargar un mensaje
        $mensaje = \App\Mensaje::with('departamento')->find($id);

        $mensaje2=[];
        for ($i=count($mensaje)-1; $i > -1; $i--) { 
            array_push($mensaje2,$mensaje[$i]);
        }
        $mensaje= $mensaje2;
        if(count($mensaje)==0){
            return response()->json(['error'=>'No existe el mensaje con id '.$id], 404);          
        }else{
            return response()->json(['mensaje'=>$mensaje], 200);
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
        // Comprobamos si el mensaje que nos están pasando existe o no.
        $mensaje=\App\Mensaje::find($id);

        if (count($mensaje)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el mensaje con id '.$id], 404);
        }      

        // Listado de campos recibidos teóricamente.
        $estado=$request->input('estado');
        $tipo=$request->input('tipo');
        $asunto=$request->input('asunto');
        $msg=$request->input('msg');
        $adjunto=$request->input('adjunto');
        $departamento_id=$request->input('departamento_id');


        // Creamos una bandera para controlar si se ha modificado algún dato.
        $bandera = false;

        // Actualización parcial de campos.
        if ($estado != null && $estado!='')
        {

            $mensaje->estado = $estado;
            $bandera=true;
        }

        if ($tipo != null && $tipo!='')
        {

            $mensaje->tipo = $tipo;
            $bandera=true;
        }

        if ($asunto != null && $asunto!='')
        {

            $mensaje->asunto = $asunto;
            $bandera=true;
        }

        if ($msg != null && $msg!='')
        {

            $mensaje->msg = $msg;
            $bandera=true;
        }

        if ($adjunto != null && $adjunto!='')
        {

            $mensaje->adjunto = $adjunto;
            $bandera=true;
        }

        if ($departamento_id != null && $departamento_id!='')
        {
            // Comprobamos si el departamento_id que nos están pasando existe o no.
            $departamento = \App\Departamento::find($departamento_id);
            if(count($departamento)==0){
                return response()->json(['error'=>'No existe el departamento con id '.$departamento_id], 404);          
            }

            $mensaje->departamento_id = $departamento_id;
            $bandera=true;
        }

        if ($bandera)
        {
            // Almacenamos en la base de datos el registro.
            if ($mensaje->save()) {

                return response()->json(['message'=>'Mensaje editado con éxito.',
                    'mensaje'=>$mensaje], 200);
            }else{
                return response()->json(['error'=>'Error al actualizar el mensaje.'], 500);
            }
            
        }
        else
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
            // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
            return response()->json(['error'=>'No se ha modificado ningún dato al mensaje.'],409);
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
        // Comprobamos si el mensaje existe o no.
        $mensaje=\App\Mensaje::find($id);

        if (count($mensaje)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el mensaje con id '.$id], 404);
        }

        // Eliminamos el mensaje .
        $mensaje->delete();

        return response()->json(['message'=>'Se ha eliminado correctamente el mensaje.'], 200);
    }

    public function mensajesDep(Request $request, $departamento_id)
    {
        if (!$request->input('estado')) {

            //cargar todos los mensajes de un departamento
            $mensajes = \App\Mensaje::where('departamento_id', $departamento_id)->get();
            
            $mensajes2=[];
            for ($i=count($mensajes)-1; $i > -1; $i--) { 
                //return count($mensajes)-1;
                array_push($mensajes2,$mensajes[$i]);
                //return count($mensajes);
            }
            $mensajes= $mensajes2;
            if(count($mensajes) == 0){

                return response()->json(['error'=>'No existen mensajes.'], 404);          
            }else{
                return response()->json(['mensajes'=>$mensajes], 200);
            }
        }
        else{

            //cargar todos los mensajes de un departamento con un estado especifico
            $mensajes = \App\Mensaje::where('departamento_id', $departamento_id)
                ->where('estado', $request->input('estado'))->get();

            if(count($mensajes) == 0){

                if ($request->input('estado') == 1) {
                    $estado = 'sin leer';
                }else if ($request->input('estado') == 2) {
                    $estado = 'leido';
                }

                return response()->json(['error'=>'No existen mensajes en estado '.$estado], 404);          
            }else{
                return response()->json(['mensajes'=>$mensajes], 200);
            }
        }
    }

}
