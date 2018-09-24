<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DateTime;
use DB;

class compraController extends Controller
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

        $compra = \App\Compra::with('proveedor')->whereRaw("created_at >= ? AND created_at <= ?", array($from->format('Y-m-d') ." 00:00:00", $to->format('Y-m-d')." 23:59:59"))->orderBy('id', 'DESC')->get();

        for ($i=0; $i < count($compra); $i++) { 
            $compra[$i]->productos=json_decode($compra[$i]->productos);
        }
        if($compra){
            return response()->json(['status'=>'ok', 'compra'=>$compra], 200); 
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


    public function add(Request $request, $id)
    {
        $agregar = \App\Stock::where('id',$id)->first();
        $agregar->stock= $agregar->stock+ $request->cantidad;

        if($agregar->save()){
            $cc=new \App\Trazas;
            $cc->cantidad=$request->cantidad;
            $cc->stock_id=$id;
            $cc->d_receptor_id=100;
            $cc->d_emisor_id=100;
            $cc->operacion_id=$request->pedido_id;
            $cc->factura=$request->factura;
            $cc->tipo='Ingreso al inventario por factura';
            $cc->u_emisor_id=$request->usuario;
            $cc->u_receptor_id=$request->usuario;
            $cc->save();
            return response()->json(['status'=>'ok', 'producto'=>$agregar], 200);
        }
        else{
            return response()->json(['error'=>'No se pudo agregar'], 404); 
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cc=new \App\Compra;
        $cc->fill($request->all());

        //return $cc;
        if($cc->save())
            return response()->json(['status'=>'ok', 'compra'=>$cc], 200);
        else
            return response()->json(['error'=>'No se pudo crear compra'], 404); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //cargar un compra
        $compra = \App\Compra::find($id);

        if(count($compra)==0){
            return response()->json(['error'=>'No existe el compra con id '.$id], 404);          
        }else{

            //$proveedor->productos = $proveedor->productos;
            return response()->json(['status'=>'ok', 'compra'=>$compra], 200);
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
        $cc=\App\Compra::where('id',$id)->first();
        //return $cc;
        $cc->productos=json_encode($request->productos);
        $cc->estado=$request->estado;
        $cc->obs_recepcion=$request->obs_recepcion;
        //$cc->fill($request->all());

        if($cc->save())
            return response()->json(['status'=>'ok', 'compra'=>$cc], 200);
        else
            return response()->json(['error'=>'No se pudo crear compra'], 404); 
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
        $cc=\App\Compra::find($id);

        if (count($cc)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el compra con id '.$id], 404);
        } 
       
        //Eliminar las relaciones(productos) en la tabla pivote
        $cc->delete();

        return response()->json(['status'=>'ok', 'message'=>'Se ha eliminado correctamente el compra.'], 200);
    }
}

