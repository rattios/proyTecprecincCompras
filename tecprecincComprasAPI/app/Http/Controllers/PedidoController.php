<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //cargar todos los pedidos
        $pedidos = \App\Pedido::with('solicitud')->with('usuario.departamento')->get();

        if(count($pedidos) == 0){
            return response()->json(['error'=>'No existen pedidos.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'pedidos'=>$pedidos], 200);
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
        //return $request->input('estado');
        // Primero comprobaremos si estamos recibiendo todos los campos obligatorios.
        if (/*!$request->input('estado') ||*/
            !$request->input('usuario_id') ||
            !$request->input('solicitud'))
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['error'=>'Faltan datos necesarios para el proceso de alta del pedido.'],422);
        }

        //validaciones
        /*$aux1 = \App\User::find($request->input('usuario_id'));
        if(count($aux1) == 0){
           // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'No existe el usuario al cual se quiere asociar el pedido.'], 409);
        } */

        //Verificar que todos los productos solicitados del pedido existen en la tabla stock
        //$solicitud = json_decode($request->input('solicitud'));
        //return json_decode($request->input('solicitud'));
        $solicitud = json_decode($request->input('solicitud'));
        //return $solicitud[0]->producto_id;
        for ($i=0; $i < count($solicitud) ; $i++) { 
            $aux2 = \App\Stock::find($solicitud[$i]->producto_id);
            if(count($aux2) == 0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'No existe el producto con id '.$solicitud[$i]->producto_id], 409);
            }   
        }    

        if($nuevoPedido=\App\Pedido::create([
            //'estado'=>$request->input('estado'),
            'estado'=>0, 
            'usuario_id'=>$request->input('usuario_id')
            ]))
        {

            //Crear las relaciones en la tabla pivote
            for ($i=0; $i < count($solicitud) ; $i++) { 

                if (property_exists($solicitud[$i], 'observaciones')) {
                    $observacionX = $solicitud[$i]->observaciones;
                }else{
                    $observacionX = null;
                }

                $nuevoPedido->solicitud()->attach($solicitud[$i]->producto_id, [
                    'cantidad' => $solicitud[$i]->cantidad,
                    'aprobado' => 0,
                    'entregado' => 0,
                    //'f_entrega' => $solicitud[$i]->f_entrega,
                    //'tipo_entrega' => $solicitud[$i]->tipo_entrega,
                    'devuelto' => 0,
                    'cancelado' => 0,
                    //'pendiente' => $solicitud[$i]->pendiente,
                    'observaciones' => $observacionX
                ]);
                   
            }
            return response()->json(['status'=>'ok', 'pedido'=>$nuevoPedido], 200);
        }else{
            return response()->json(['error'=>'Error al crear el pedido.'], 500);
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
        //cargar un pedido
        $pedido = \App\Pedido::with('solicitud')->find($id);

        if(count($pedido)==0){
            return response()->json(['error'=>'No existe el pedido con id '.$id], 404);          
        }else{
            return response()->json(['status'=>'ok', 'pedido'=>$pedido], 200);
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
        // Comprobamos si el pedido que nos están pasando existe o no.
        $pedido=\App\Pedido::find($id);

        if (count($pedido)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el pedido con id '.$id], 404);
        }      

        // Listado de campos recibidos teóricamente.
        $estado=$request->input('estado');
        $solicitud=$request->input('solicitud');

        // Creamos una bandera para controlar si se ha modificado algún dato.
        $bandera = false;

        // Actualización parcial de campos.
        if ($estado != null && $estado!='')
        {
            $pedido->estado = $estado;
            $bandera=true;
        }

        if ($solicitud) {
            $solicitud = json_decode($request->input('solicitud'));
            for ($i=0; $i < count($solicitud) ; $i++) { 
                  $pedido->solicitud()->updateExistingPivot($solicitud[$i]->id, [
                    'cantidad' => $solicitud[$i]->pivot->cantidad,
                    'aprobado' => $solicitud[$i]->pivot->aprobado,
                    'entregado' => $solicitud[$i]->pivot->entregado,
                    'f_entrega' => $solicitud[$i]->pivot->f_entrega,
                    'tipo_entrega' => $solicitud[$i]->pivot->tipo_entrega,
                    'devuelto' => $solicitud[$i]->pivot->devuelto,
                    'cancelado' => $solicitud[$i]->pivot->cancelado,
                    'pendiente' => $solicitud[$i]->pivot->pendiente,
                    'observaciones' => $solicitud[$i]->pivot->observaciones
                    ]
                    );
            }

            $bandera=true; 
        }

        if ($bandera)
        {
            // Almacenamos en la base de datos el registro.
            if ($pedido->save()) {
                return response()->json(['status'=>'ok','pedido'=>$pedido], 200);
            }else{
                return response()->json(['error'=>'Error al actualizar el pedido.'], 500);
            }
            
        }
        else
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
            // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
            return response()->json(['error'=>'No se ha modificado ningún dato al pedido.'],409);
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
        // Comprobamos si el pedido que nos están pasando existe o no.
        $pedido=\App\Pedido::find($id);

        if (count($pedido)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el pedido con id '.$id], 404);
        } 
       
        //Eliminar las relaciones(solicitud) en la tabla pivote
        $pedido->solicitud()->detach();

        // Eliminamos el pedido.
        $pedido->delete();

        return response()->json(['status'=>'ok', 'message'=>'Se ha eliminado correctamente el pedido.'], 200);
    }
}
