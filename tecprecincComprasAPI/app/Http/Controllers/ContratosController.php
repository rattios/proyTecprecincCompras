<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class ContratosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //cargar todos los proveedores con los productos que ofrecen
        $contratos = \App\Contratos::all();

        if(count($contratos) == 0){
            return response()->json(['error'=>'No existen contratos.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'contratos'=>$contratos], 200);
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
        $cc=new \App\contratos;
        $cc->fill($request->all());

        if($cc->save())
            return response()->json(['status'=>'ok', 'contratos'=>$cc], 200);
        else
            return response()->json(['error'=>'No se pudo crear cc'], 404); 
    }


    public function relaciones(Request $request)
    {

        $contrato=new \App\Contratos;
        $contrato->fill($request->all());
        $contrato->save();

        $centro_costos=json_decode($request->centro_costos);
        //return $centro_costos;
        for ($i=0; $i < count($centro_costos); $i++) { 
            $contrato->departamentos()->attach($centro_costos[$i]->departamento_id,['contro_costos_id'=>$centro_costos[$i]->contro_costos_id]);
        }
        
        return response()->json(['status'=>'ok', 'contratos'=>$contrato], 200);
 
    }

    public function relaciones_actualizar(Request $request,$id)
    {
        $contrato=\App\Contratos::find($id);
        
        $contrato->departamentos()->detach();

        for ($i=0; $i < 4; $i++) { 
            $contrato->departamentos()->attach($request->departamento_id,['contro_costos_id'=>$request->contro_costos_id]);
        }
        
        return response()->json(['status'=>'ok', 'contratos'=>$contrato], 200);

       /*DB::table('contrato_departamento_centrocosto')
                ->where('id', $id)
                ->update(['contratos_id' => $request->contratos_id,'departamento_id' => $request->departamento_id, 'contro_costos_id' =>$request->contro_costos_id]);

        return response()->json(['status'=>'ok'], 200);*/
 
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
        $Contratos = \App\Contratos::where('id',$id)->with('departamentos')->with('centrocostos')->get();
        
        if(count($Contratos)==0){
            

        
            return response()->json(['error'=>'No existe el Contratos con id '.$id], 404);          
        }else{
            for ($i=0; $i < count($Contratos); $i++) { 
                for ($j=0; $j < count($Contratos[$i]->departamentos); $j++) {
                $Contratos[$i]->departamentos[$j]->cc=[];
                $cc=[];
                    for ($k=0; $k < count($Contratos[$i]->centrocostos); $k++) { 
                        if ($Contratos[$i]->id == $Contratos[$i]->centrocostos[$k]->pivot->contratos_id && $Contratos[$i]->departamentos[$j]->id == $Contratos[$i]->centrocostos[$k]->pivot->departamento_id && $Contratos[$i]->departamentos[$j]->pivot->contro_costos_id == $Contratos[$i]->centrocostos[$k]->pivot->contro_costos_id) {
                            array_push($cc,$Contratos[$i]->centrocostos[$k]);
                            //return $Contratos[$i]->centrocostos[$k];
                        }
                    }
                    $Contratos[$i]->departamentos[$j]->cc=$cc;
                    $cc=[];
                }
            }

            $con= new \App\Contratos;
            $con->id= $Contratos[0]->id;
            $con->nombre= $Contratos[0]->nombre;
            $con->cliente= $Contratos[0]->cliente;
            $con->vigencia= $Contratos[0]->vigencia;
            $con->centro_costos_id= $Contratos[0]->centro_costos_id;
            $con->created_at= $Contratos[0]->created_at;
            $con->departamentos= [];


            $d=[];
           
            $departamento=new \App\Departamento;
            $departamento->id=$Contratos[0]->departamentos[0]->id;
            $departamento->nombre=$Contratos[0]->departamentos[0]->nombre;
            $departamento->codigo=$Contratos[0]->departamentos[0]->codigo;
            $departamento->telefono=$Contratos[0]->departamentos[0]->telefono;
            $departamento->cc=[];
            array_push($d, $departamento);
            for ($i=0; $i < count($Contratos); $i++) { 
                for ($j=0; $j < count($Contratos[$i]->departamentos); $j++) {
                    //return $this->repetidos($Contratos[$i]->departamentos[$j]->id,$d);
                    if ($this->repetidos($Contratos[$i]->departamentos[$j]->id,$d)) {
                        $departamento=new \App\Departamento;
                        $departamento->id=$Contratos[$i]->departamentos[$j]->id;
                        $departamento->nombre=$Contratos[$i]->departamentos[$j]->nombre;
                        $departamento->codigo=$Contratos[$i]->departamentos[$j]->codigo;
                        $departamento->telefono=$Contratos[$i]->departamentos[$j]->telefono;
                        $departamento->cc=[];
                        array_push($d, $departamento);
                    }
                }
            }
            $cc=[];
            for ($k=0; $k < count($d); $k++) { 
                for ($i=0; $i < count($Contratos); $i++) { 
                    for ($j=0; $j < count($Contratos[$i]->departamentos); $j++) {
                        if ($Contratos[$i]->departamentos[$j]->id == $d[$k]->id) {
                                array_push($cc,$Contratos[$i]->departamentos[$j]->cc);
                                //return $Contratos[$i]->centrocostos[$k];
                        }
                    }
                }
                $d[$k]->cc=$cc;
                $cc=[];
            }


            $con->departamentos= $d;
            //$proveedor->productos = $proveedor->productos;
            return response()->json(['status'=>'ok', 'Contratos'=>$con], 200);
        }
    }
    function repetidos($id,$array){
       // return $array[0]->id;
        for ($i=0; $i < count($array); $i++) { 
            if ($array[$i]->id==$id) {
                return false;
            }
        }
        return true;
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
        $cc=\App\contratos::where('id',$id)->first();;
        $cc->fill($request->all());

        if($cc->save())
            return response()->json(['status'=>'ok', 'contratos'=>$cc], 200);
        else
            return response()->json(['error'=>'No se pudo crear cc'], 404); 
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
        $cc=\App\contratos::find($id);

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

