<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TrazasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Trazas = \App\Trazas::with('stock')->with('departamento_emisor')->with('departamento_receptor')->with('usuario_emisor')->with('usuario_receptor')->get();

        if(count($Trazas) == 0){
            return response()->json(['error'=>'No existen Trazas.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'Trazas'=>$Trazas], 200);
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
        $cc=new \App\Trazas;
        $cc->fill($request->all());

        if($cc->save())
            return response()->json(['status'=>'ok', 'CentroCostos'=>$cc], 200);
        else
            return response()->json(['error'=>'No se pudo crear cc'], 404); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Trazas = \App\Trazas::where('id',$id)->with('stock')->with('departamento_emisor')->with('departamento_receptor')->with('usuario_emisor')->with('usuario_receptor')->get();

        if(count($Trazas) == 0){
            return response()->json(['error'=>'No existen Trazas.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'Trazas'=>$Trazas], 200);
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
        //cargar un proveedor
        $cc=\App\Trazas::where('id',$id)->first();;
        $cc->fill($request->all());

        if($cc->save())
            return response()->json(['status'=>'ok', 'Trazas'=>$cc], 200);
        else
            return response()->json(['error'=>'No se pudo crear Trazas'], 404); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Comprobamos si el proveedor que nos están pasando existe o no.
        $cc=\App\Trazas::find($id);

        if (count($cc)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el proveedor con id '.$id], 404);
        } 
       
        //Eliminar las relaciones(productos) en la tabla pivote
        $cc->delete();

        return response()->json(['status'=>'ok', 'message'=>'Se ha eliminado correctamente el cc.'], 200);
    }
}
