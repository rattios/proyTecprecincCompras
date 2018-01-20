<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
        $productos = \App\StockDepartamento::with('categoria')->with('departamento')->get();

        if(count($productos) == 0){
            return response()->json(['error'=>'No existen productos en el stock de los departamentos.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'productos'=>$productos], 200);
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
        if ( !$request->input('nombre') || 
             !$request->input('stock') || 
             !$request->input('stock_min') ||
             !$request->input('categoria_id') ||
             !$request->input('departamento_id'))
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['error'=>'Faltan datos necesarios para el proceso de alta.'],422);
        } 

        // Comprobamos si la categoria que nos están pasando existe o no.
        $categoria = \App\Categoria::find($request->input('categoria_id'));
        if(count($categoria)==0){
            return response()->json(['error'=>'No existe la categoría con id '.$request->input('categoria_id')], 404);          
        } 

        // Comprobamos si el departamento que nos están pasando existe o no.
        $departamento = \App\Departamento::find($request->input('departamento_id'));
        if(count($departamento)==0){
            return response()->json(['error'=>'No existe el departamento con id '.$request->input('departamento_id')], 404);          
        } 
        
        $aux = \App\StockDepartamento::where('nombre', $request->input('nombre'))
            ->where('categoria_id', $categoria->id)
            ->where('departamento_id', $request->input('departamento_id'))->get();
        if(count($aux)!=0){
           // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Ya existe un producto con esas características en en el departamento.'], 409);
        }

        if ($request->input('codigo')) {
            $aux2 = \App\StockDepartamento::where('codigo', $request->input('codigo'))
            ->where('departamento_id', $request->input('departamento_id'))->get();
            if(count($aux2)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe un producto con el código '.$request->input('codigo').' en el departamento.'], 409);
            }
        }

        if ($request->input('proveedor_id')) {
            $proveedor = \App\Proveedor::find($request->input('proveedor_id'));
            if(count($proveedor)==0){
                return response()->json(['error'=>'No existe el proveedor con id '.$request->input('proveedor_id')], 404);          
            }
        }

        //Creamos el producto en el departamento
        $producto = \App\StockDepartamento::create($request->all());

        return response()->json(['status'=>'ok', 'message'=>'Producto creado con éxito.',
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
        $producto = \App\StockDepartamento::with('categoria')->with('departamento')->find($id);

        if(count($producto)==0){
            return response()->json(['error'=>'No existe el producto con id '.$id.' en el stock de departamentos.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'producto'=>$producto], 200);
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
        // Comprobamos si el producto que nos están pasando existe o no en el stock de departamentos.
        $producto = \App\StockDepartamento::find($id);

        if(count($producto)==0){
            return response()->json(['error'=>'No existe el producto con id '.$id.' en el stock.'], 404);          
        }   

        // Listado de campos recibidos teóricamente.
        $nombre=$request->input('nombre');
        $codigo=$request->input('codigo');
        $stock=$request->input('stock'); // num existencias
        $stock_min=$request->input('stock_min');
        $categoria_id=$request->input('categoria_id');
        $proveedor_id=$request->input('proveedor_id');
        $departamento_id=$request->input('departamento_id');
                
        // Creamos una bandera para controlar si se ha modificado algún dato.
        $bandera = false;

        // Actualización parcial de campos.
        if ($nombre != null && $nombre!='')
        {
            $aux = \App\StockDepartamento::where('nombre', $request->input('nombre'))
                ->where('id', '<>', $producto->id)->get();
            if(count($aux)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro producto con ese nombre'], 409);
            }

            $producto->nombre = $nombre;
            $bandera=true;
        }

        if ($codigo != null && $codigo!='')
        {
            $aux2 = \App\StockDepartamento::where('codigo', $request->input('codigo'))
                ->where('id', '<>', $producto->id)->get();
            if(count($aux2)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro producto con ese código'], 409);
            }

            $producto->codigo = $codigo;
            $bandera=true;
        }

        if ($precio != null && $precio!='')
        {
            $producto->precio = $precio;
            $bandera=true;
        }

        if ($stock != null && $stock!='')
        {
            $producto->stock = $stock;
            $bandera=true;
        }

        if ($peps != null && $peps!='')
        {
            $producto->peps = $peps;
            $bandera=true;
        }

        if ($valor_reposicion != null && $valor_reposicion!='')
        {
            $producto->valor_reposicion = $valor_reposicion;
            $bandera=true;
        }

        if ($stock_min != null && $stock_min!='')
        {
            $producto->stock_min = $stock_min;
            $bandera=true;
        }

        if ($partida_parcial != null && $partida_parcial!='')
        {
            $producto->partida_parcial = $partida_parcial;
            $bandera=true;
        }

        if ($categoria_id != null && $categoria_id !='')
         {
            // Comprobamos si la categoria que nos están pasando existe o no.
            $categoria=\App\Categoria::find($categoria_id);

            if (count($categoria)==0)
            {
                // Devolvemos error codigo http 404
                return response()->json(['error'=>'No existe la categoría con id '.$categoria_id], 404);
            }

            $producto->categoria_id = $categoria_id;
            $bandera=true;
        }

        if ($proveedor_id != null && $proveedor_id!='')
        {
            // Comprobamos si el proveedor que nos están pasando existe o no.
            $proveedor=\App\Proveedor::find($proveedor_id);

            if (count($proveedor)==0)
            {
                // Devolvemos error codigo http 404
                return response()->json(['error'=>'No existe el proveedor con id '.$proveedor_id], 404);
            }

            $producto->proveedor_id = $proveedor_id;
            $bandera=true;
        }

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
