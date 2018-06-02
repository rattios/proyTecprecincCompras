<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //DB::enableQueryLog();

        //cargar todos los departamentos
        
        //Consulta Eloquent
        //$departamentos = \App\Departamento::all();
        //$departamentos = \App\User::toSql();

        //Consulta Raw SQL
        $departamentos = DB::select('select * from `departamentos`');

        //$log = DB::getQueryLog();
        //var_dump($log);
        //print_r($log);
        //$lastQuery = end($log);

        if(count($departamentos) == 0){
            return response()->json(['error'=>'No existen departamentos.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'departamentos'=>$departamentos], 200);
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
        //DB::enableQueryLog();

        // Primero comprobaremos si estamos recibiendo todos los campos.
        if ( !$request->input('nombre') || !$request->input('codigo') )
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['error'=>'Faltan datos necesarios para el proceso de alta.'],422);
        } 

        // Comprobamos si ya existe un departamento con esos atributos.

        //Consulta Eloquent
        /*$departamento = \App\Departamento::where('nombre', $request->input('nombre'))
        ->orWhere('codigo', $request->input('codigo'))->get();*/

        //Consulta Raw SQL
        $departamento = DB::select('select * from `departamentos` where `nombre` = ? or `codigo` = ?', [
            $request->input('nombre'),
            $request->input('codigo'),
            $request->input('telefono')
            ]);

        if(count($departamento)!=0){
           // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Ya existe un departamento con esas características.'], 409);
        }

        //Creamos el departamento
        $nuevoDepartamento = \App\Departamento::create($request->all());

        //$log = DB::getQueryLog();
        
        return response()->json(['status'=>'ok', 'message'=>'Departamento creado con éxito.',
                 'departamento'=>$nuevoDepartamento], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //DB::enableQueryLog();

        //cargar un departamento

        //Consulta eloquent
        //$departamento = \App\Departamento::find($id);

        //Consulta Raw SQL
        $departamento = DB::select('select * from `departamentos` where `departamentos`.`id` = ? limit 1', [
            $id
            ]);


        //$log = DB::getQueryLog();

        if(count($departamento)==0){
            return response()->json(['error'=>'No existe el departamento con id '.$id], 404);          
        }else{

            return response()->json(['status'=>'ok', 'departamento'=>$departamento], 200);
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
        // Comprobamos si el departamento que nos están pasando existe o no.
        $departamento=\App\Departamento::find($id);

        if (count($departamento)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el departamento con id '.$id], 404);
        }      

        // Listado de campos recibidos teóricamente.
        $nombre=$request->input('nombre');
        $codigo=$request->input('codigo');
        $telefono=$request->input('telefono');

        // Creamos una bandera para controlar si se ha modificado algún dato.
        $bandera = false;

        // Actualización parcial de campos.
        if ($telefono != null && $telefono!='')
        {
            $departamento->telefono = $telefono;
            $bandera=true;
        }

        if ($nombre != null && $nombre!='')
        {
            $aux = \App\Departamento::where('nombre', $request->input('nombre'))
                ->where('id', '<>', $departamento->id)->get();

            if(count($aux)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro departamento con ese nombre.'], 409);
            }

            $departamento->nombre = $nombre;
            $bandera=true;
        }

        if ($codigo != null && $codigo!='')
        {
            $aux = \App\Departamento::where('codigo', $request->input('codigo'))
            ->where('id', '<>', $departamento->id)->get();

            if(count($aux)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro departamento con ese codigo.'], 409);
            }

            $departamento->codigo = $codigo;
            $bandera=true;
        }



        if ($bandera)
        {
            // Almacenamos en la base de datos el registro.
            if ($departamento->save()) {
                return response()->json(['status'=>'ok', 'message'=>'Departamento editado con éxito.',
                    'departamento'=>$departamento], 200);
            }else{
                return response()->json(['error'=>'Error al actualizar el departamento.'], 500);
            }
            
        }
        else
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
            // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
            return response()->json(['error'=>'No se ha modificado ningún dato al departamento.'],409);
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
        // Comprobamos si la departamento existe o no.
        $departamento=\App\Departamento::find($id);

        if (count($departamento)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el departamento con id '.$id], 404);
        }


        $usuarios = $departamento->usuarios;
        $productos = $departamento->productos;

        if (sizeof($usuarios) > 0 || sizeof($productos) > 0)
        {
            // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Este departamento no puede ser eliminado.'], 409);
        }

        // Eliminamos la departamento si no tiene relaciones.
        $departamento->delete();

        return response()->json(['status'=>'ok', 'message'=>'Se ha eliminado correctamente el departamento.'], 200);
    }
}
