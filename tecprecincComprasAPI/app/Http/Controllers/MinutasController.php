<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DateTime;
use DB;

class MinutasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $from=new DateTime($request->input('inicio'));
        $to=new DateTime($request->input('fin'));

        $Presupuesto = \App\Minutas::whereRaw("created_at >= ? AND created_at <= ?", array($from->format('Y-m-d') ." 00:00:00", $to->format('Y-m-d')." 23:59:59"))->orderBy('id', 'DESC')->get();

        for ($i=0; $i < count($Presupuesto); $i++) { 
            $Presupuesto[$i]->productos=json_decode($Presupuesto[$i]->productos);
        }
        if($Presupuesto){
            return response()->json(['status'=>'ok', 'presupuesto'=>$Presupuesto], 200); 
        }else{
            return response()->json(['error'=>'error'], 404);         
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
        $Minutas=new \App\Minutas;
        $Minutas->fill($request->all());

        if($Minutas->save())
            return response()->json(['status'=>'ok', 'Minutas'=>$Minutas], 200);
        else
            return response()->json(['error'=>'No se pudo crear Minutas'], 404); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //cargar un proveedor
        $Minutas = \App\Minutas::find($id);

        if(count($Minutas)==0){
            return response()->json(['error'=>'No existe el Minutas con id '.$id], 404);          
        }else{
            return response()->json(['status'=>'ok', 'Minutas'=>$Minutas], 200);
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
        $Minutas=\App\Minutas::where('id',$id)->first();;
        $Minutas->fill($request->all());

        if($Minutas->save())
            return response()->json(['status'=>'ok', 'Minutas'=>$Minutas], 200);
        else
            return response()->json(['error'=>'No se pudo crear Minutas'], 404); 
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
        $Minutas=\App\Minutas::find($id);

        if (count($Minutas)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el proveedor con id '.$id], 404);
        } 
       
        //Eliminar las relaciones(productos) en la tabla pivote
        $Minutas->delete();

        return response()->json(['status'=>'ok', 'message'=>'Se ha eliminado correctamente el Minutas.'], 200);
    }
}

