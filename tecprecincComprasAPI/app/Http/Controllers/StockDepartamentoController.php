<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class StockDepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //cargar el stock de todos los departamentos
        $productos = \App\StockDepartamento::with('producto')->with('departamento')->get();

        if(count($productos) == 0){
            return response()->json(['error'=>'No existen productos en el stock de los departamentos.'], 404);          
        }else{
            return response()->json(['productos'=>$productos], 200);
        } 
    }

    public function StockDepartamento(Request $request)
    {
        $departamento_id=$request->input('departamento_id');
        $productos=[];
        $stockdepartamentos=DB::select("SELECT * FROM `stockdepartamentos` WHERE `departamento_id`=".$departamento_id);
       for ($i=0; $i < count($stockdepartamentos); $i++) { 
           //$stockdepartamentos[$i]->productos=$productos;
           $stockdepartamentos[$i]->nombre='';
           $stockdepartamentos[$i]->codigo='';
           $p=DB::select("SELECT `nombre`,`codigo` FROM `stock` WHERE `id`=".$stockdepartamentos[$i]->stock_id);
            for ($j=0; $j < count($p); $j++) { 
                //array_push($stockdepartamentos[$i]->productos,$p[$j]);
                $stockdepartamentos[$i]->nombre=$p[0]->nombre;
                $stockdepartamentos[$i]->codigo=$p[0]->codigo;
            }
        }
        
        if(count($stockdepartamentos) == 0){
            return response()->json(['error'=>'No existen productos en el stock de los departamentos.'], 404);          
        }else{
            return response()->json(['productos'=>$stockdepartamentos], 200);
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
        if ( !$request->input('stock_id') || 
             !$request->input('stock') ||
             !$request->input('departamento_id'))
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['error'=>'Faltan datos necesarios para el proceso de alta.'],422);
        } 

        // Comprobamos si el stock_id que nos están pasando existe o no.
        $stock = \App\Stock::find($request->input('stock_id'));
        if(count($stoc)==0){
            return response()->json(['error'=>'No existe el producto con id '.$request->input('stock_id').' en el stock.'], 404);          
        } 

        // Comprobamos si el departamento que nos están pasando existe o no.
        $departamento = \App\Departamento::find($request->input('departamento_id'));
        if(count($departamento)==0){
            return response()->json(['error'=>'No existe el departamento con id '.$request->input('departamento_id')], 404);          
        } 

        $productoEnDep = \App\StockDepartamento::where('stock_id', $request->input('stock_id'))
                ->where('departamento_id', $request->input('departamento_id'))->get();

        if(count($productoEnDep)==0){
            return response()->json(['error'=>'Ya exite ese producto en el departamento.'], 409);          
        } 

        //Creamos el producto en el departamento
        $producto = \App\StockDepartamento::create($request->all());

        return response()->json(['message'=>'Producto creado con éxito.',
                 'producto'=>$producto], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //cargar un producto del stock de los departamentos
        $producto = \App\StockDepartamento::with('producto')->with('departamento')->find($id);

        if(count($producto)==0){
            return response()->json(['error'=>'No existe el producto con id '.$id.' en el stock de departamentos.'], 404);          
        }else{
            return response()->json(['producto'=>$producto], 200);
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
        // Comprobamos si el producto que nos están pasando existe o no en el stock de departamentos.
        $producto = \App\StockDepartamento::find($id);

        if(count($producto)==0){
            return response()->json(['error'=>'No existe el producto con id '.$id.' en el stock.'], 404);          
        }   

        // Listado de campos recibidos teóricamente.
        $stock=$request->input('stock'); // num existencias
        $stock_min=$request->input('stock_min');
        //$departamento_id=$request->input('departamento_id');
                
        // Creamos una bandera para controlar si se ha modificado algún dato.
        $bandera = false;

        // Actualización parcial de campos.
        if ($stock != null && $stock!='')
        {
            $producto->stock = $stock;
            $bandera=true;
        }

        if ($stock_min != null && $stock_min!='')
        {
            $producto->stock_min = $stock_min;
            $bandera=true;
        }

        /*if ($departamento_id != null && $departamento_id!='')
        {
            // Comprobamos si el departamento que nos están pasando existe o no.
            $departamento=\App\Departamento::find($departamento_id);

            if (count($departamento)==0)
            {
                // Devolvemos error codigo http 404
                return response()->json(['error'=>'No existe el departamento con id '.$departamento_id], 404);
            }

            $producto->departamento_id = $departamento_id;
            $bandera=true;
        }*/

        if ($bandera)
        {
            // Almacenamos en la base de datos el registro.
            if ($producto->save()) {
                return response()->json(['status'=>'ok', 'message'=>'Producto editado con éxito.',
                        'producto'=>$producto], 200);
            }else{
                return response()->json(['error'=>'Error al actualizar el producto.'], 500);
            }
            
        }
        else
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
            // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
            return response()->json(['error'=>'No se ha modificado ningún dato al producto.'],409);
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
        //
    }
}
