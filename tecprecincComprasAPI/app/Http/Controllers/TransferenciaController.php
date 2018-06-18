<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class TransferenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        //cargar todas las transferencias
        $transferencias = \App\Transferencia::with('departamento')
            /*->with('stockDep')*/->with('stockCentral')->get();

        if(count($transferencias) == 0){
            return response()->json(['error'=>'No existen transferencias.'], 404);          
        }else{

            for ($i=0; $i < count($transferencias) ; $i++) { 

                // Cargar el stock actual del departamento.
                $stock_dep = \App\StockDepartamento::where('stock_id', $transferencias[$i]->stock_id)
                    ->where('departamento_id', $transferencias[$i]->departamento_id)->get();

                if(count($stock_dep)==0){
                    return response()->json(['error'=>'No existe el producto con id '.$transferencias->stock_id.' en el stock del departamento.'], 404);          
                }else{
                    $transferencias[$i]->stock_dep = $stock_dep[0];
                }
            }
            return response()->json(['transferencias'=>$transferencias], 200);
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
        if ( !$request->input('cantidad_transf') ||
            !$request->input('stock_id') ||
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

        // Comprobamos si el stock_id que nos están pasando existe o no en el stock del departamento.
        $stock_dep = \App\StockDepartamento::where('stock_id', $request->input('stock_id'))
            ->where('departamento_id', $request->input('departamento_id'))->get();
        if(count($stock_dep)==0){
            return response()->json(['error'=>'No existe el producto con id '.$request->input('stock_id').' en el stock del departamento.'], 404);          
        }

        if($stock_dep[0]->stock <= 0){
           // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'No se puede generar una transferencia con el stock del departamento en cero.'], 409);
        }

        //validaciones sobre el producto en el stock central
        $producto=\App\Stock::find($request->input('stock_id'));

        if (count($producto)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el producto con id '.$request->input('stock_id').' en el stock.'], 404);
        }

        if (!$producto->tipo_id || $producto->tipo_id == 0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'El producto a transferir debe tener un tipo asociado.'], 404);
        }else{
            $tipo_producto=\App\Tipo::find($producto->tipo_id);
        } 

        if($tipo_producto->nombre == 'CONSUMO'){
            return response()->json(['error'=>'El proceso de transferencia no esta implementado para vienes de consumo.'],409);
        }
        else if($tipo_producto->nombre == 'USO'){
            
            if($nuevaTransf=\App\Transferencia::create([
                'estado'=> 1,
                'cantidad_transf'=> $request->input('cantidad_transf'),
                'stock_id'=> $request->input('stock_id'),
                'departamento_id'=> $request->input('departamento_id')
            ]))
            {
                /*Aqui crear el msg para informar al departamento que tiene una nueva transferencia pendiente*/
                $nuevoMsg=\App\Mensaje::create([
                    'estado'=> 1,
                    'tipo'=> 1,
                    'asunto'=> 'Nueva Transferencia',
                    'msg'=> 'Tienes una nueva transferencia.',
                    /*'adjunto'=> 'adjunto',*/
                    'departamento_id'=> $request->input('departamento_id')
                ]);

               return response()->json(['message'=>'Transferencia creada con éxito.',
                 'Transferencia'=>$nuevaTransf], 200);
            }else{
                return response()->json(['error'=>'Error al crear la transferencia.'], 500);
            }
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
        //cargar una transferencia
        $transferencia = \App\Transferencia::with('departamento')/*->with('stockDep')*/->find($id);

        if(count($transferencia)==0){
            return response()->json(['error'=>'No existe la transferencia con id '.$id], 404);          
        }else{
            return response()->json(['transferencia'=>$transferencia], 200);
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
        // Comprobamos si la transferencia que nos están pasando existe o no.
        $transferencia=\App\Transferencia::find($id);

        if (count($transferencia)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe la transferencia con id '.$id], 404);
        }      

        // Listado de campos recibidos teóricamente.
        $estado=$request->input('estado');
        $cantidad_transf=$request->input('cantidad_transf');
        $stock_id=$request->input('stock_id');
        $departamento_id=$request->input('departamento_id');


        // Creamos una bandera para controlar si se ha modificado algún dato.
        $bandera = false;

        // Actualización parcial de campos.
        if ($estado != null && $estado!='')
        {

            $transferencia->estado = $estado;
            $bandera=true;
        }

        if ($cantidad_transf != null && $cantidad_transf!='')
        {

            $transferencia->cantidad_transf = $cantidad_transf;
            $bandera=true;
        }

        if ($stock_id != null && $stock_id!='')
        {
            // Comprobamos si el stock_id que nos están pasando existe o no en el stock del departamento.
            $stock_dep = \App\StockDepartamento::find($stock_id);
            if(count($stock_dep)==0){
                return response()->json(['error'=>'No existe el producto con id '.$stock_id.' en el stock del departamento.'], 404);          
            }

            $transferencia->stock_id = $stock_id;
            $bandera=true;
        }

        if ($departamento_id != null && $departamento_id!='')
        {
            // Comprobamos si el departamento_id que nos están pasando existe o no.
            $departamento = \App\Departamento::find($departamento_id);
            if(count($departamento)==0){
                return response()->json(['error'=>'No existe el departamento con id '.$departamento_id], 404);          
            }

            $transferencia->departamento_id = $departamento_id;
            $bandera=true;
        }

        if ($bandera)
        {
            // Almacenamos en la base de datos el registro.
            if ($transferencia->save()) {

                return response()->json(['message'=>'Transferencia editada con éxito.',
                    'transferencia'=>$transferencia], 200);
            }else{
                return response()->json(['error'=>'Error al actualizar la transferencia.'], 500);
            }
            
        }
        else
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
            // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
            return response()->json(['error'=>'No se ha modificado ningún dato a la transferencia.'],409);
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
        // Comprobamos si el transferencia existe o no.
        $transferencia=\App\Transferencia::find($id);

        if (count($transferencia)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe la transferencia con id '.$id], 404);
        }

        // Eliminamos la transferencia .
        $transferencia->delete();

        return response()->json(['message'=>'Se ha eliminado correctamente la transferencia.'], 200);
    }

    public function transfsDep(Request $request, $departamento_id)
    {
        if (!$request->input('estado')) {

            //cargar todas las transferencias de un departamento
            $transferencias = \App\Transferencia::where('departamento_id', $departamento_id)
                ->with('departamento')/*->with('stockDep')*/->with('stockCentral')->get();

            if(count($transferencias) == 0){

                return response()->json(['error'=>'No existen transferencias.'], 404);          
            }else{
                
                for ($i=0; $i < count($transferencias) ; $i++) { 

                    // Cargar el stock actual del departamento.
                    $stock_dep = \App\StockDepartamento::where('stock_id', $transferencias[$i]->stock_id)
                        ->where('departamento_id', $transferencias[$i]->departamento_id)->get();

                    if(count($stock_dep)==0){
                        return response()->json(['error'=>'No existe el producto con id '.$transferencias->stock_id.' en el stock del departamento.'], 404);          
                    }else{
                        $transferencias[$i]->stock_dep = $stock_dep[0];
                    }
                }
                return response()->json(['transferencias'=>$transferencias], 200);
            }
        }
        else{

            //cargar todas las transferencias de un departamento con un estado especifico
            $transferencias = \App\Transferencia::where('departamento_id', $departamento_id)
                ->where('estado', $request->input('estado'))
                ->with('departamento')/*->with('stockDep')*/->with('stockCentral')->get();

            if(count($transferencias) == 0){

                if ($request->input('estado') == 1) {
                    $estado = 'pendiente';
                }else if ($request->input('estado') == 2) {
                    $estado = 'aprobada';
                }else if ($request->input('estado') == 3) {
                    $estado = 'rechazada';
                }

                return response()->json(['error'=>'No existen transferencias en estado '.$estado], 404);          
            }else{

                for ($i=0; $i < count($transferencias) ; $i++) { 

                    // Cargar el stock actual del departamento.
                    $stock_dep = \App\StockDepartamento::where('stock_id', $transferencias[$i]->stock_id)
                        ->where('departamento_id', $transferencias[$i]->departamento_id)->get();

                    if(count($stock_dep)==0){
                        return response()->json(['error'=>'No existe el producto con id '.$transferencias->stock_id.' en el stock del departamento.'], 404);          
                    }else{
                        $transferencias[$i]->stock_dep = $stock_dep[0];
                    }
                }
                return response()->json(['transferencias'=>$transferencias], 200);
            }
        }
    }

    public function aprobarTrasf($id)
    {
        //cargar la transferencia
        $transferencia = \App\Transferencia::/*with('stockDep')->*/find($id);

        if(count($transferencia)==0){
            return response()->json(['error'=>'No existe la transferencia con id '.$id], 404);          
        }else{

            if ($transferencia->estado == 2) {
                return response()->json(['error'=>'Esta transferencia ya está aprobada.'],409);
            }

            // Comprobamos si el stock_id que nos están pasando existe o no en el stock del departamento.
            $stock_dep = \App\StockDepartamento::where('stock_id', $transferencia->stock_id)
                ->where('departamento_id', $transferencia->departamento_id)->get();
            if(count($stock_dep)==0){
                return response()->json(['error'=>'No existe el producto con id '.$request->input('stock_id').' en el stock del departamento.'], 404);          
            }

            if($stock_dep[0]->stock <= 0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'No se puede aprobar una transferencia con el stock del departamento en cero.'], 409);
            }

            $producto=\App\Stock::find($transferencia->stock_id);

            if (count($producto)==0)
            {
                // Devolvemos error codigo http 404
                return response()->json(['error'=>'No existe el producto con id '.$transferencia->stock_id.' en el stock.'], 404);
            }

            if (!$producto->tipo_id || $producto->tipo_id == 0)
            {
                // Devolvemos error codigo http 404
                return response()->json(['error'=>'El producto a transferir debe tener un tipo asociado.'], 409);
            }else{
                $tipo_producto=\App\Tipo::find($producto->tipo_id);
            } 

            if($tipo_producto->nombre == 'CONSUMO'){
                return response()->json(['error'=>'El proceso de transferencia no esta implementado para vienes de consumo.'],409);
            }
            else if($tipo_producto->nombre == 'USO'){
                
                //Descontar del stock del departamento la cantidad a transferir
                $descontar = $stock_dep[0]->stock - $transferencia->cantidad_transf;

                DB::table('stockdepartamentos')
                    ->where('id', $stock_dep[0]->id)
                    ->update(['stock' => $descontar]);

                //Aumentar el stock pricipal con la catidad a transferir
                $aumentar = $producto->stock + $transferencia->cantidad_transf;

                $producto->stock = $aumentar;
                $producto->save();

                $transferencia->estado = 2;
                $transferencia->save();

                /*Aqui crear msg para informar al departamento de compras de que aprobaron una transferencia*/
                $nuevoMsg=\App\Mensaje::create([
                    'estado'=> 1,
                    'tipo'=> 1,
                    'asunto'=> 'Transferencia Aprobada',
                    'msg'=> 'Una transferencia fué aprobada.',
                    /*'adjunto'=> 'adjunto',*/
                    'departamento_id'=> 1
                ]);

                return response()->json(['message'=>'Se ha aprobado la transferencia.', 'transferencia'=>$transferencia], 200);
            }
        } 
    }

    /*
    Manejo de transferencia para el departamento de compras,
    de stock principal a secundario o viceversa
    */
    /*
    NOTA: 
        Emisor: 
        departamento_id 100 -> stock principal
        departamento_id 101 -> stock secundario
    */
    public function transferencia(Request $request)
    {
        // Primero comprobaremos si estamos recibiendo todos los campos.
        if ( !$request->input('cantidad_transf') ||
            !$request->input('stock_id') ||
            !$request->input('departamento_id') 
        )
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['error'=>'Faltan datos necesarios para el proceso de alta.'],422);
        } 

        //validaciones sobre el producto en el stock central
        $producto=\App\Stock::find($request->input('stock_id'));

        if (count($producto)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el producto con id '.$request->input('stock_id').' en el stock.'], 404);
        }

        //emisor es stock principal
        if ($request->input('departamento_id') == 100) {
            //Almacen de destino
            $almacen = 2;
            if ($producto->stock <= 0) {
                // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'No se puede generar una transferencia con el stock pricipal en cero.'], 409);
            }else{
                //Descontar del stock del pricipal la cantidad a transferir
                $descontar = $producto->stock - $request->input('cantidad_transf');

                //Aumentar el stock secundario con la catidad a transferir
                $aumentar = $producto->stock2 + $request->input('cantidad_transf');

                $producto->stock = $descontar;
                $producto->stock2 = $aumentar;
                $producto->save();
            }
        }
        //emisor es stock secundario
        else if ($request->input('departamento_id') == 101){
            //Almacen de destino
            $almacen = 1;
            if ($producto->stock2 <= 0) {
                // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'No se puede generar una transferencia con el stock secundario en cero.'], 409);
            }else{
                //Descontar del stock del secundario la cantidad a transferir
                $descontar = $producto->stock2 - $request->input('cantidad_transf');

                //Aumentar el stock principal con la catidad a transferir
                $aumentar = $producto->stock + $request->input('cantidad_transf');

                $producto->stock2 = $descontar;
                $producto->stock = $aumentar;
                $producto->save();
            }
        } 

            
        if($nuevaTransf=\App\Transferencia::create([
            'estado'=> 2,
            'cantidad_transf'=> $request->input('cantidad_transf'),
            'stock_id'=> $request->input('stock_id'),
            'departamento_id'=> 1,
            'tipo'=> 1,
            'almacen'=> $almacen
        ]))
        {

           return response()->json(['message'=>'Transferencia creada con éxito.',
             'transferencia'=>$nuevaTransf], 200);
        }else{
            return response()->json(['error'=>'Error al crear la transferencia.'], 500);
        }
                
    }

    /*
    Manejo de devoluciones de un departamento_id hacia el departamento de compras
    */
    /*
    NOTA: 
        Receptor: 
        almacen 1 -> stock principal
        almacen 2 -> stock secundario
    */
    public function devolucion(Request $request)
    {
        // Primero comprobaremos si estamos recibiendo todos los campos.
        if ( !$request->input('cantidad_transf') ||
            !$request->input('stock_id') ||
            !$request->input('departamento_id') ||
            !$request->input('almacen') ||
            !$request->input('usuario_id') 
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

        // Comprobamos si el usuario que nos están pasando existe o no.
        $usuario = \App\User::find($request->input('usuario_id'));
        if(count($usuario)==0){
            return response()->json(['error'=>'No existe el usuario con id '.$request->input('usuario_id')], 404);          
        }

        // Comprobamos si el stock_id que nos están pasando existe o no en el stock del departamento.
        $stock_dep = \App\StockDepartamento::where('stock_id', $request->input('stock_id'))
            ->where('departamento_id', $request->input('departamento_id'))
            ->where('usuario_id', $request->input('usuario_id'))
            ->get();
        if(count($stock_dep)==0){
            return response()->json(['error'=>'No existe el producto con id '.$request->input('stock_id').' en el stock del departamento.'], 404);          
        }

        if($stock_dep[0]->stock <= 0){
           // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'No se puede generar una devolución con el stock del departamento en cero.'], 409);
        }

        //validaciones sobre el producto en el stock central
        $producto=\App\Stock::find($request->input('stock_id'));

        if (count($producto)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el producto con id '.$request->input('stock_id').' en el stock central.'], 404);
        }

        if (!$producto->tipo_id || $producto->tipo_id == 0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'El producto a devolver debe tener un tipo asociado.'], 404);
        }else{
            $tipo_producto=\App\Tipo::find($producto->tipo_id);
        } 

        if($tipo_producto->nombre == 'CONSUMO'){
            return response()->json(['error'=>'El proceso de devolución no esta implementado para vienes de consumo.'],409);
        }
        else if($tipo_producto->nombre == 'USO'){

            //Descontar del stock del departamento la cantidad a devolver
            $descontar = $stock_dep[0]->stock - $request->input('cantidad_transf');

            DB::table('stockdepartamentos')
                ->where('id', $stock_dep[0]->id)
                ->update(['stock' => $descontar]);

            //Aumentar el stock central con la catidad a devolver
            //Almacen principal
            if ($request->input('almacen') == 1) {
                $aumentar = $producto->stock + $request->input('cantidad_transf');

                $producto->stock = $aumentar;
                $producto->save();
            //Almacen secundario
            }else if ($request->input('almacen') == 2){
                $aumentar = $producto->stock2 + $request->input('cantidad_transf');

                $producto->stock2 = $aumentar;
                $producto->save();
            }
            
            /*Nota: en este caso usuario_id es el usuario al cual se le esta descontando*/
            if($nuevaDev=\App\Transferencia::create([
                'estado'=> 2,
                'cantidad_transf'=> $request->input('cantidad_transf'),
                'stock_id'=> $request->input('stock_id'),
                'departamento_id'=> $request->input('departamento_id'),
                'tipo' => 2,
                'almacen'=> $request->input('almacen'),
                'usuario_id'=> $request->input('usuario_id')
            ]))
            {
                /*Aqui crear el msg para informar al departamento de compras que tiene una nueva devolución*/
                $nuevoMsg=\App\Mensaje::create([
                    'estado'=> 1,
                    'tipo'=> 1,
                    'asunto'=> 'Nueva Devolución',
                    'msg'=> 'Tienes una nueva devolución.',
                    /*'adjunto'=> 'adjunto',*/
                    'departamento_id'=> 1
                ]);

               return response()->json(['message'=>'Devolución creada con éxito.',
                 'devolucion'=>$nuevaDev], 200);
            }else{
                return response()->json(['error'=>'Error al crear la devolución.'], 500);
            }
        }

          
    }

    /*
    Manejo de transferencia del departamento de compras
    a cualquier otro departamento
    */
    /*
    NOTA: 
        Almacen: 
        departamento_id 100 -> stock principal
        departamento_id 101 -> stock secundario
    */
    public function transferenciaPatrimonial(Request $request)
    {
        // Primero comprobaremos si estamos recibiendo todos los campos.
        if ( !$request->input('cantidad_transf') ||
            !$request->input('stock_id') ||
            !$request->input('departamento_id') ||
            !$request->input('almacen') ||
            !$request->input('receptor_id') ||
            !$request->input('usuario_id') 
        )
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['error'=>'Faltan datos necesarios para el proceso de alta.'],422);
        } 

        // Comprobamos si el receptor_id que nos están pasando existe o no.
        $departamento = \App\Departamento::find($request->input('receptor_id'));
        if(count($departamento)==0){
            return response()->json(['error'=>'No existe el departamento con id '.$request->input('receptor_id')], 404);          
        }

        // Comprobamos si el usuario_id que nos están pasando existe o no.
        $usuario = \App\User::find($request->input('usuario_id'));
        if(count($usuario)==0){
            return response()->json(['error'=>'No existe el usuario con id '.$request->input('usuario_id')], 404);          
        }
        // Comprobamos si el stock_id que nos están pasando existe o no en el stock del departamento receptor.
        $stock_dep = \App\StockDepartamento::where('stock_id', $request->input('stock_id'))
            ->where('departamento_id', $request->input('receptor_id'))
            ->where('usuario_id', $request->input('usuario_id'))
            ->get();
        if(count($stock_dep)==0){
            $bandera = false;          
        }else{
            $bandera = true; 
        }

        // Comprobamos si el departamento_id (emisor) que nos están pasando existe o no.
        $departamento = \App\Departamento::find($request->input('departamento_id'));
        if(count($departamento)==0){
            return response()->json(['error'=>'No existe el departamento con id '.$request->input('departamento_id')], 404);          
        }

        //validaciones sobre el producto en el stock central
        $producto=\App\Stock::find($request->input('stock_id'));

        if (count($producto)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el producto con id '.$request->input('stock_id').' en el stock central.'], 404);
        }

        //almacen emisor es stock principal
        if ($request->input('almacen') == 1) {
            if ($producto->stock <= 0) {
                // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'No se puede generar una transferencia con el stock pricipal en cero.'], 409);
            }
        }
        //almacen emisor es stock secundario
        else if ($request->input('almacen') == 2){
            if ($producto->stock2 <= 0) {
                // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'No se puede generar una transferencia con el stock secundario en cero.'], 409);
            }
        } 

        if (!$producto->tipo_id || $producto->tipo_id == 0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'El producto a transferir debe tener un tipo asociado.'], 404);
        }else{
            $tipo_producto=\App\Tipo::find($producto->tipo_id);
        } 

        if($tipo_producto->nombre == 'CONSUMO'){
            return response()->json(['error'=>'El proceso de transferencia no esta implementado para vienes de consumo.'],409);
        }
        else if($tipo_producto->nombre == 'USO'){

            if ($bandera) {
                //Aumentar del stock del departamento la cantidad a transferir
                $aumentar = $stock_dep[0]->stock + $request->input('cantidad_transf');

                DB::table('stockdepartamentos')
                    ->where('id', $stock_dep[0]->id)
                    ->update(['stock' => $aumentar]);
            }else{
                $NewProdEnDep = new \App\StockDepartamento;
                $NewProdEnDep->stock_id = $request->input('stock_id');
                $NewProdEnDep->stock = $request->input('cantidad_transf');
                $NewProdEnDep->stock_min = 0;
                $NewProdEnDep->departamento_id = $request->input('receptor_id');
                $NewProdEnDep->usuario_id = $request->input('usuario_id');
                $NewProdEnDep->save();
            }
            

            //Descontar el stock central con la catidad a transferir
            //Almacen principal
            if ($request->input('almacen') == 1) {
                $descontar = $producto->stock - $request->input('cantidad_transf');

                $producto->stock = $descontar;
                $producto->save();
            //Almacen secundario
            }else if ($request->input('almacen') == 2){
                $descontar = $producto->stock2 - $request->input('cantidad_transf');

                $producto->stock2 = $descontar;
                $producto->save();
            }
            
            /*Nota: en este caso usuario_id es el usuario que se le esta agregando*/
            if($nuevaTransf=\App\Transferencia::create([
                'estado'=> 2,
                'cantidad_transf'=> $request->input('cantidad_transf'),
                'stock_id'=> $request->input('stock_id'),
                'departamento_id'=> $request->input('departamento_id'),
                'tipo' => 3,
                'almacen'=> $request->input('almacen'),
                'receptor_id'=> $request->input('receptor_id'),
                'usuario_id'=> $request->input('usuario_id')
            ]))
            {
                /*Aqui crear el msg para informar al departamento receptor que tiene una nueva transferencia*/
                $nuevoMsg=\App\Mensaje::create([
                    'estado'=> 1,
                    'tipo'=> 1,
                    'asunto'=> 'Nueva Transferencia',
                    'msg'=> 'Tienes una nueva transferencia.',
                    /*'adjunto'=> 'adjunto',*/
                    'departamento_id'=> 1
                ]);

               return response()->json(['message'=>'Transferencia creada con éxito.',
                 'transferencia'=>$nuevaTransf], 200);
            }else{
                return response()->json(['error'=>'Error al crear la transferencia.'], 500);
            }
        }
     
    }

    public function indexTranfPuras()
    {
        
        //cargar todas las transferencias puras
        $transferencias = \App\Transferencia::where('tipo', 1)->with('stockCentral')->orderBy('id', 'desc')->get();

        if(count($transferencias) == 0){
            return response()->json(['error'=>'No existen transferencias puras.'], 404);          
        }else{

            return response()->json(['transferencias'=>$transferencias], 200);
        }    
    }

    public function indexDevoluciones()
    {
        
        //cargar todas las devoluciones 
        $devoluciones = \App\Transferencia::with('departamento')
            ->with('usuario')
            ->where('tipo', 2)->with('stockCentral')->orderBy('id', 'desc')->get();

        if(count($devoluciones) == 0){
            return response()->json(['error'=>'No existen devoluciones.'], 404);          
        }else{

            return response()->json(['devoluciones'=>$devoluciones], 200);
        }    
    }

    public function indexTranfPatri()
    {
        
        //cargar todas las transferencias patrimoniales
        $transferencias = \App\Transferencia::with('receptor')
            ->with('usuario')
            ->where('tipo', 3)->with('stockCentral')->orderBy('id', 'desc')->get();

        if(count($transferencias) == 0){
            return response()->json(['error'=>'No existen transferencias patrimoniales.'], 404);          
        }else{

            return response()->json(['transferencias'=>$transferencias], 200);
        }    
    }



}
