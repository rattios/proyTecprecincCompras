<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use DateTime;

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
        $pedidos = \App\Pedido::with('solicitud')->with('centro_costos')->with('contratos')->with('usuario.departamento')->orderBy('id', 'desc')->get();


        if(count($pedidos) == 0){
            return response()->json(['error'=>'No existen pedidos.'], 404);          
        }else{

            $categorias = \App\Categoria::with('tipo')->with('rubro')->get();

            for ($i=0; $i < count($pedidos) ; $i++) { 
                for ($j=0; $j < count($pedidos[$i]->solicitud); $j++) { 
                    for ($k=0; $k < count($categorias); $k++) { 
                        if ($pedidos[$i]->solicitud[$j]->categoria_id == $categorias[$k]->id ) {
                            $pedidos[$i]->solicitud[$j]->categoria = $categorias[$k];
                        }
                    }
                    
                }
                
            }
            
            for ($i=0; $i < count($pedidos) ; $i++) { 
                for ($j=0; $j < count($pedidos[$i]->solicitud); $j++) { 
                    $pedidos[$i]->solicitud[$j]->centro_costos=$pedidos[$i]->centro_costos;
                    $pedidos[$i]->solicitud[$j]->contratos=$pedidos[$i]->contratos;
                } 
            }

            return response()->json(['status'=>'ok', 'pedidos'=>$pedidos], 200);
        } 
    }
    public function aprobar(Request $request)
    {
        $usuario= \App\User::find($request->input('usuario_id'));
        $departamento=$request->input('departamento_id');
        //cargar todos los pedidos
        if ($usuario->rol==1 || $usuario->rol==0) {
           // $departamento=$usuario->departamento_id;
            $pedidos = \App\Pedido::where('estado', 0)->where('aprobar', 0)->with('departamento')->with('solicitud')->with('contratos')->with('centro_costos')->with('usuario.departamento')->orderBy('id', 'desc')->get();
            $auxPedidos=[];
            for ($i=0; $i < count($pedidos); $i++) { 
                if ($pedidos[$i]->departamento_id==$departamento) {
                   array_push($auxPedidos,$pedidos[$i]);
                }
            }

            $pedidos=$auxPedidos;
            if(count($pedidos) == 0){
                return response()->json(['status'=>'ok', 'pedidos'=>[]], 200);          
            }else{

                $categorias = \App\Categoria::with('tipo')->with('rubro')->get();

                for ($i=0; $i < count($pedidos) ; $i++) { 
                    for ($j=0; $j < count($pedidos[$i]->solicitud); $j++) { 
                        for ($k=0; $k < count($categorias); $k++) { 
                            if ($pedidos[$i]->solicitud[$j]->categoria_id == $categorias[$k]->id ) {
                                $pedidos[$i]->solicitud[$j]->categoria = $categorias[$k];
                            }
                        }
                    }
                }

                for ($i=0; $i < count($pedidos) ; $i++) { 
                    for ($j=0; $j < count($pedidos[$i]->solicitud); $j++) { 
                        $pedidos[$i]->solicitud[$j]->centro_costos=$pedidos[$i]->centro_costos;
                        $pedidos[$i]->solicitud[$j]->contratos=$pedidos[$i]->contratos;
                    } 
                }

                $centrocostos = \App\CentroCostos::with('contratos')->get();
                return response()->json(['status'=>'ok', 'pedidos'=>$pedidos, 'centrocostos'=>$centrocostos], 200);
            } 
        }
        
    }
    public function index0()
    {
        //cargar todos los pedidos
        $pedidos = \App\Pedido::where('estado', 0)->where('aprobar', 1)->with('departamento')->with('solicitud')->with('contratos')->with('centro_costos')->with('usuario.departamento')->orderBy('id', 'desc')->get();


        if(count($pedidos) == 0){
            return response()->json(['status'=>'ok', 'pedidos'=>[]], 200);          
        }else{

            $categorias = \App\Categoria::with('tipo')->with('rubro')->get();

            for ($i=0; $i < count($pedidos) ; $i++) { 
                for ($j=0; $j < count($pedidos[$i]->solicitud); $j++) { 
                    for ($k=0; $k < count($categorias); $k++) { 
                        if ($pedidos[$i]->solicitud[$j]->categoria_id == $categorias[$k]->id ) {
                            $pedidos[$i]->solicitud[$j]->categoria = $categorias[$k];
                        }
                    }
                }
            }

            for ($i=0; $i < count($pedidos) ; $i++) { 
                for ($j=0; $j < count($pedidos[$i]->solicitud); $j++) { 
                    $pedidos[$i]->solicitud[$j]->centro_costos=$pedidos[$i]->centro_costos;
                    $pedidos[$i]->solicitud[$j]->contratos=$pedidos[$i]->contratos;
                } 
            }
            $centrocostos = \App\CentroCostos::with('contratos')->get();
            return response()->json(['status'=>'ok', 'pedidos'=>$pedidos, 'centrocostos'=>$centrocostos], 200);
        } 
    }
    public function index1()
    {
        //cargar todos los pedidos
        $pedidos = \App\Pedido::where('estado', 1)->with('solicitud')->with('departamento')->with('contratos')->with('centro_costos')->with('usuario.departamento')->orderBy('id', 'desc')->get();


        if(count($pedidos) == 0){
            return response()->json(['status'=>'ok', 'pedidos'=>[]], 200);          
        }else{

            $categorias = \App\Categoria::with('tipo')->with('rubro')->get();

            for ($i=0; $i < count($pedidos) ; $i++) { 
                for ($j=0; $j < count($pedidos[$i]->solicitud); $j++) { 
                    for ($k=0; $k < count($categorias); $k++) { 
                        if ($pedidos[$i]->solicitud[$j]->categoria_id == $categorias[$k]->id ) {
                            $pedidos[$i]->solicitud[$j]->categoria = $categorias[$k];
                        }
                    }
                    
                }
                
            }

            for ($i=0; $i < count($pedidos) ; $i++) { 
                for ($j=0; $j < count($pedidos[$i]->solicitud); $j++) { 
                    $pedidos[$i]->solicitud[$j]->centro_costos=$pedidos[$i]->centro_costos;
                    $pedidos[$i]->solicitud[$j]->contratos=$pedidos[$i]->contratos;
                } 
            }
            $centrocostos = \App\CentroCostos::with('contratos')->get();
            return response()->json(['status'=>'ok', 'pedidos'=>$pedidos, 'centrocostos'=>$centrocostos], 200);
        } 
    }
    public function index2(Request $request)
    {
        $from=new DateTime($request->input('inicio'));
        $to=new DateTime($request->input('fin'));
        //cargar todos los pedidos
        $pedidos = \App\Pedido::where('estado', 2)->with('solicitud')->with('departamento')->with('contratos')->with('centro_costos')->with('usuario.departamento')->whereRaw("created_at >= ? AND created_at <= ?", array($from->format('Y-m-d') ." 00:00:00", $to->format('Y-m-d')." 23:59:59"))->orderBy('id', 'DESC')->get();


        if(count($pedidos) == 0){
           return response()->json(['status'=>'ok', 'pedidos'=>[]], 200);          
        }else{

            $categorias = \App\Categoria::with('tipo')->with('rubro')->get();

            for ($i=0; $i < count($pedidos) ; $i++) { 
                for ($j=0; $j < count($pedidos[$i]->solicitud); $j++) { 
                    for ($k=0; $k < count($categorias); $k++) { 
                        if ($pedidos[$i]->solicitud[$j]->categoria_id == $categorias[$k]->id ) {
                            $pedidos[$i]->solicitud[$j]->categoria = $categorias[$k];
                        }
                    }
                    
                }
                
            }

            for ($i=0; $i < count($pedidos) ; $i++) { 
                for ($j=0; $j < count($pedidos[$i]->solicitud); $j++) { 
                    $pedidos[$i]->solicitud[$j]->centro_costos=$pedidos[$i]->centro_costos;
                    $pedidos[$i]->solicitud[$j]->contratos=$pedidos[$i]->contratos;
                } 
            }

            $centrocostos = \App\CentroCostos::with('contratos')->get();
            return response()->json(['status'=>'ok', 'pedidos'=>$pedidos, 'centrocostos'=>$centrocostos], 200);
        } 
    }
    public function index4()
    {
        //cargar todos los pedidos
        $pedidos = \App\Pedido::where('estado', 4)->with('solicitud')->with('departamento')->with('contratos')->with('centro_costos')->with('usuario.departamento')->orderBy('id', 'desc')->get();


        if(count($pedidos) == 0){
            return response()->json(['status'=>'ok', 'pedidos'=>[]], 200);          
        }else{

            $categorias = \App\Categoria::with('tipo')->with('rubro')->get();

            for ($i=0; $i < count($pedidos) ; $i++) { 
                for ($j=0; $j < count($pedidos[$i]->solicitud); $j++) { 
                    for ($k=0; $k < count($categorias); $k++) { 
                        if ($pedidos[$i]->solicitud[$j]->categoria_id == $categorias[$k]->id ) {
                            $pedidos[$i]->solicitud[$j]->categoria = $categorias[$k];
                        }
                    }
                    
                }
                
            }

            for ($i=0; $i < count($pedidos) ; $i++) { 
                for ($j=0; $j < count($pedidos[$i]->solicitud); $j++) { 
                    $pedidos[$i]->solicitud[$j]->centro_costos=$pedidos[$i]->centro_costos;
                    $pedidos[$i]->solicitud[$j]->contratos=$pedidos[$i]->contratos;
                } 
            }

            $centrocostos = \App\CentroCostos::with('contratos')->get();
            return response()->json(['status'=>'ok', 'pedidos'=>$pedidos, 'centrocostos'=>$centrocostos], 200);
        } 
    }


    public function index_departamentos($id)
    {

        //cargar todos los pedidos
        $pedidos = \App\Pedido::where('usuario_id',$id)->with('departamento')->with('solicitud')->with('usuario.departamento')->orderBy('id', 'desc')->get();
        $usuario = \App\User::where('id',$id)->with('departamento')->get();
        
        $transferencias = \App\Transferencia::where('departamento_id',$usuario[0]->departamento->id)->with('departamento')/*->with('stockDep')*/->with('stockCentral')->get();

        for ($i=0; $i < count($transferencias) ; $i++) { 
            // Cargar el stock actual del departamento.
            $stock_dep = \App\StockDepartamento::where('stock_id', $transferencias[$i]->stock_id)
                ->where('departamento_id', $transferencias[$i]->departamento_id)->get();

            if(count($stock_dep)==0){
                //return response()->json(['error'=>'No existe el producto con id '.$transferencias->stock_id.' en el stock del departamento.'], 404);          
            }else{
                $transferencias[$i]->stock_dep = $stock_dep[0];
            }
        }

        if(count($pedidos) == 0){
            return response()->json(['error'=>'No existen pedidos.'], 404);          
        }else{

            $categorias = \App\Categoria::with('tipo')->with('rubro')->get();

            for ($i=0; $i < count($pedidos) ; $i++) { 
                for ($j=0; $j < count($pedidos[$i]->solicitud); $j++) { 
                    for ($k=0; $k < count($categorias); $k++) { 
                        if ($pedidos[$i]->solicitud[$j]->categoria_id == $categorias[$k]->id ) {
                            $pedidos[$i]->solicitud[$j]->categoria = $categorias[$k];
                        }
                    }
                    
                }
                
            }

            for ($i=0; $i < count($transferencias); $i++) { 
                if ($transferencias[$i]->estado==1) {
                    $transferencias[$i]->estado2='En curso';
                }else if ($transferencias[$i]->estado==2) {
                    $transferencias[$i]->estado2='Aprobada';
                }else if ($transferencias[$i]->estado==3) {
                    $transferencias[$i]->estado2='Rechazada';
                }
            }

            return response()->json([
                'status'=>'ok',
                'pedidos'=>$pedidos,
                'transferencias'=>$transferencias
            ], 200);
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
            'estado'=>$request->input('estado'), 
            'usuario_id'=>$request->input('usuario_id'),
            'centro_costos_id'=>$request->input('centro_costos_id'),
            'contrato_id'=>$request->input('contrato_id'),
            'aprobar'=>$request->input('aprobar'),
            'observaciones'=>$request->input('observaciones'),
            'departamento_id'=>$request->input('departamento_id')
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
                    'observaciones' => $observacionX,
                    'centro_costos_id' => $solicitud[$i]->centro_costos_id
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
        //$pedido = \App\Pedido::with('solicitud')->find($id);

        $pedidos = \App\Pedido::where('id', $id)->with('solicitud')->with('contratos')->with('centro_costos')->with('usuario.departamento')->get();


        if(count($pedidos) == 0){
           return response()->json(['status'=>'ok', 'pedidos'=>[]], 200);          
        }else{

            $categorias = \App\Categoria::with('tipo')->with('rubro')->get();

            for ($i=0; $i < count($pedidos) ; $i++) { 
                for ($j=0; $j < count($pedidos[$i]->solicitud); $j++) { 
                    for ($k=0; $k < count($categorias); $k++) { 
                        if ($pedidos[$i]->solicitud[$j]->categoria_id == $categorias[$k]->id ) {
                            $pedidos[$i]->solicitud[$j]->categoria = $categorias[$k];
                        }
                    }
                    
                }
                
            }

            for ($i=0; $i < count($pedidos) ; $i++) { 
                for ($j=0; $j < count($pedidos[$i]->solicitud); $j++) { 
                    $pedidos[$i]->solicitud[$j]->centro_costos=$pedidos[$i]->centro_costos;
                    $pedidos[$i]->solicitud[$j]->contratos=$pedidos[$i]->contratos;
                } 
            }

            $centrocostos = \App\CentroCostos::with('contratos')->get();
            return response()->json(['status'=>'ok', 'pedidos'=>$pedidos, 'centrocostos'=>$centrocostos], 200);
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
     public function editar_observacion(Request $request, $id)
    {
        // Comprobamos si el pedido que nos están pasando existe o no.
        $pedido=\App\Pedido::find($id);
        $pedido->observaciones = $request->input('observaciones');

        if ($pedido->save()) {
            return response()->json(['status'=>'ok','pedido'=>$pedido], 200);
        }else{
            return response()->json(['error'=>'Error al actualizar el pedido.'], 500);
        }
    }
    public function editar_cc(Request $request, $id)
    {   //solicitud();
        $pedido=\App\Pedido::find($id);
        //return response()->json(['status'=>'ok','pedido'=>$pedido], 200);
        $preferencias = json_decode($request->input('solicitud'));
           // return response()->json(['status'=>'ok','preferencias'=>$preferencias], 200);
            //Eliminar las relaciones(preferencias) en la tabla pivote
            $pedido->solicitud()->detach();

            //Agregar las nuevas relaciones(preferencias) en la tabla pivote
            for ($i=0; $i < count($preferencias) ; $i++) { 
                  $pedido->solicitud()->attach($preferencias[$i]->id, [
                    'cantidad' => $preferencias[$i]->pivot->cantidad,
                    'aprobado' => $preferencias[$i]->pivot->aprobado,
                    'entregado' => $preferencias[$i]->pivot->entregado,
                    'f_entrega' => $preferencias[$i]->pivot->f_entrega,
                    'tipo_entrega' => $preferencias[$i]->pivot->tipo_entrega,
                    'devuelto' => $preferencias[$i]->pivot->devuelto,
                    'cancelado' => $preferencias[$i]->pivot->cancelado,
                    'pendiente' => $preferencias[$i]->pivot->pendiente,
                    'observacion' => $preferencias[$i]->pivot->observacion,
                    'centro_costos_id' => $preferencias[$i]->pivot->centro_costos_id
                ]);
            }
        if ($pedido->save()) {
            return response()->json(['status'=>'ok','pedido'=>$pedido], 200);
        }else{
            return response()->json(['error'=>'Error al actualizar el pedido.'], 500);
        }
    }
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
        $aprobar=$request->input('aprobar');
        $observaciones=$request->input('observaciones');
        // Creamos una bandera para controlar si se ha modificado algún dato.
        $bandera = false;

        // Actualización parcial de campos.
        if ($estado != null && $estado!='')
        {
            $pedido->estado = $estado;
            $bandera=true;
        }
        // Actualización parcial de campos.
        if ($aprobar != null && $aprobar!='')
        {
            $pedido->aprobar = $aprobar;
            $bandera=true;
        }
        if ($observaciones != null && $observaciones!='')
        {
            $pedido->observaciones = $observaciones;
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

    public function picking(Request $request)
    {
        if (!$request->input('picking'))
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['error'=>'Faltan datos necesarios para el proceso de picking.'],422);
        }

        $picking = json_decode($request->input('picking'));
        //$picking = $request->input('picking');

        //producto sin tipo
        if ($picking->tipo_id == 0) {
          //  return response()->json(['error'=>'El producto de tener asociado un tipo para poder realizar el proceso de picking.'],409);
        }
        //bienes de consumo
        else if ($picking->tipo_id == 1) {
            
            if ($picking->almacen == 'principal') {
                //Descontar del stock principal
                $descontar = $picking->stock - $picking->pivot->cantidad;

                DB::table('stock')
                    ->where('id', $picking->id)
                    ->update(['stock' => $descontar]);

                $picking->stock = $descontar;
            }
            else if($picking->almacen == 'secundario'){
                //Descontar del stock secundario
                $descontar = $picking->stock2 - $picking->pivot->cantidad;

                DB::table('stock')
                    ->where('id', $picking->id)
                    ->update(['stock2' => $descontar]);

                $picking->stock2 = $descontar;
            }
            
            DB::table('pedido_stock')
                ->where('pedido_id', $picking->pivot->pedido_id)
                ->where('stock_id', $picking->pivot->stock_id)
                ->update(['entregado' => 1,'cantidad_entregada' => 1, 'observacion' =>$picking->observacion]);
            
            $picking->pivot->entregado = 1;

            return response()->json(['message'=>'Se ha descontado la cantidad solicitada del stock.', 'picking'=>$picking], 200);

        //vienes de uso
        }else if($picking->tipo_id == 2){

            if ($picking->almacen == 'principal') {
                //Descontar del stock principal
                $descontar = $picking->stock - $picking->pivot->cantidad;

                DB::table('stock')
                    ->where('id', $picking->id)
                    ->update(['stock' => $descontar]);
                

                $picking->stock = $descontar;
            }else{
                //Descontar del stock secundario
                $descontar = $picking->stock2 - $picking->pivot->cantidad;

                DB::table('stock')
                    ->where('id', $picking->id)
                    ->update(['stock2' => $descontar]);

                $picking->stock2 = $descontar;
            }

            DB::table('pedido_stock')
                ->where('pedido_id', $picking->pivot->pedido_id)
                ->where('stock_id', $picking->pivot->stock_id)
                ->update(['entregado' => 1,'cantidad_entregada' => 1, 'observacion' =>$picking->observacion]);

            $picking->pivot->entregado = 1;

            //verificar si no existe el producto en el departamento para crearlo
            $productoEnDep = \App\StockDepartamento::where('stock_id', $picking->pivot->stock_id)
                ->where('departamento_id', $picking->departamento->id)
                ->where('usuario_id', $picking->usuario->id)
                ->get();

            //Si no existe lo creo:
            if(count($productoEnDep)==0){
                $NewProdEnDep = new \App\StockDepartamento;
                $NewProdEnDep->stock_id = $picking->pivot->stock_id;
                $NewProdEnDep->stock = $picking->pivot->cantidad;
                $NewProdEnDep->stock_min = 0;
                $NewProdEnDep->departamento_id = $picking->departamento->id;
                $NewProdEnDep->usuario_id = $picking->usuario->id;
                if($NewProdEnDep->save()){
                   return response()->json(['message'=>'Se ha descontado la cantidad solicitada del stock.', 'picking'=>$picking], 200);
                }else{
                    return response()->json(['error'=>'Error al crear producto en el stock del departamento.'], 500);
                }                          
            }else{
                $productoEnDep[0]->stock = $productoEnDep[0]->stock + $picking->pivot->cantidad;

                if($productoEnDep[0]->save()){
                   return response()->json(['message'=>'Se ha descontado la cantidad solicitada del stock.', 'picking'=>$picking], 200);
                }else{
                    return response()->json(['error'=>'Error al actualizar producto en el stock del departamento.'], 500);
                }
            } 
        }
        //servicios
        else if ($picking->tipo_id == 3) {
            return response()->json(['error'=>'El proceso de picking no esta implementado para servicios.'],409);
        }
        
    }

    /*buscar los departamentos que contien un stock_id(producto) y su stock(existencias) es mayor a cero*/
    public function ubicarProducto($stock_id)
    {
        $departamentos = \App\StockDepartamento::with('departamento')
            ->with('usuario')
            ->where('stock_id', $stock_id)
            ->where('stock', '>', 0)->get();

        if (count($departamentos)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No hay existencias del producto solicitado en ningún departamento.'], 404);
        } 

        return response()->json(['departamentos'=>$departamentos], 200);
    }

}
