<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //cargar todos los proveedores con los productos que ofrecen
        $Alamacen = \App\Almacen::all();

        if(count($Alamacen) == 0){
            return response()->json(['error'=>'No existen Almacen.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'Alamacen'=>$Alamacen], 200);
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
        $Almacen=new \App\Almacen;
        $Almacen->fill($request->all());

        if($Almacen->save())
            return response()->json(['status'=>'ok', 'Almacen'=>$Almacen], 200);
        else
            return response()->json(['error'=>'No se pudo crear Almacen'], 404); 
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
        $Almacen = \App\Almacen::find($id);

        if(count($Almacen)==0){
            return response()->json(['error'=>'No existe el Almacen con id '.$id], 404);          
        }else{
            return response()->json(['status'=>'ok', 'Almacen'=>$Almacen], 200);
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
        $Almacen=\App\Almacen::where('id',$id)->first();;
        $Almacen->fill($request->all());

        if($Almacen->save())
            return response()->json(['status'=>'ok', 'Almacen'=>$Almacen], 200);
        else
            return response()->json(['error'=>'No se pudo crear Almacen'], 404); 
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
        $Almacen=\App\Almacen::find($id);

        if (count($Almacen)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el proveedor con id '.$id], 404);
        } 
       
        //Eliminar las relaciones(productos) en la tabla pivote
        $Almacen->delete();

        return response()->json(['status'=>'ok', 'message'=>'Se ha eliminado correctamente el Almacen.'], 200);
    }
}

