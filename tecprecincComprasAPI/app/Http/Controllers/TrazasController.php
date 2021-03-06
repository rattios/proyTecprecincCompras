<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DateTime;
use DB;

class TrazasController extends Controller
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

        $Trazas = \App\Trazas::with('stock')->with('departamento_emisor')->with('departamento_receptor')->with('usuario_emisor')->with('usuario_receptor')->whereRaw("created_at >= ? AND created_at <= ?", array($from->format('Y-m-d') ." 00:00:00", $to->format('Y-m-d')." 23:59:59"))->orderBy('id', 'DESC')->get();
        //return $Trazas;
        if(!$Trazas){
            return response()->json(['error'=>'No existen Trazas.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'from'=>$from->format('Y-m-d H:i:s'),'to'=>$to->format('Y-m-d H:i:s'),'Trazas'=>$Trazas], 200);
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
    public function show($id,Request $request)
    {
        $from=new DateTime($request->input('inicio'));
        //return $from->format('Y-m-d H:i:s');
        $to=new DateTime($request->input('fin'));
        $Trazas = \App\Trazas::where('stock_id',$id)->with('stock')->with('departamento_emisor')->with('departamento_receptor')->with('usuario_emisor')->with('usuario_receptor')->whereRaw("created_at >= ? AND created_at <= ?", array($from->format('Y-m-d') ." 00:00:00", $to->format('Y-m-d')." 23:59:59"))/*where(DB::raw('DATE_FORMAT(created_at,"%d/%m/%Y")'), '>=', $request->input('inicio'))
            ->where(DB::raw('DATE_FORMAT(created_at,"%d/%m/%Y")'), '<=', $request->input('fin'))*/->orderBy('id', 'DESC')->get();
        //return $Trazas;
        if(!$Trazas){
            return response()->json(['error'=>'No existen Trazas.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'from'=>$from->format('Y-m-d H:i:s'),'to'=>$to->format('Y-m-d H:i:s'),'Trazas'=>$Trazas], 200);
        }
    }

    public function show2($id,Request $request)
    {
        
        $Trazas = \App\Trazas::where('stock_id',$id)->where('operacion_id',$request->input('operacion_id'))->with('stock')->with('departamento_emisor')->with('departamento_receptor')->with('usuario_emisor')->with('usuario_receptor')->get();
        //return $Trazas;
        if(!$Trazas){
            return response()->json(['error'=>'No existen Trazas.'], 404);          
        }else{
            return response()->json(['status'=>'ok','Trazas'=>$Trazas], 200);
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
