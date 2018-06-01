<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use JWTAuth;

class StockController extends Controller
{
    /*Retorna los productos que puede ver el departamento       
     al cual pertenece el usuario que hace la peticion*/       
     public function stockPermitido()      
     {     
       
         $currentUser = JWTAuth::parseToken()->authenticate();     
       
         if ($currentUser) {       
            if ($currentUser->rol!=0) {

                 $dep = \App\Departamento::with('permisos_productos')->find($currentUser->departamento_id);        
                 $productos = $dep->permisos_productos;

                 $centrocostos = \App\CentroCostos::with('contratos')->get();
           
                 return response()->json(['productos'=>$productos, 'centrocostos'=>$centrocostos], 200);  
            }else if($currentUser->rol==0){
                $centrocostos = \App\CentroCostos::with('contratos')->get();
                $productos=DB::select("SELECT id,nombre,codigo,stock,stock2,categoria_id,tipo_id,rubro_id,precio,stock_min,stock2_min FROM  `stock` WHERE 1 ");

                 return response()->json(['productos'=>$productos, 'centrocostos'=>$centrocostos], 200); 
            } 
         }else{        
             return response()->json(['error'=>'Usuario no encontrado.'], 404);        
         }     
       
     }

     public function stockTransferencias()      
     {     
       
         $currentUser = JWTAuth::parseToken()->authenticate();     
       
         if ($currentUser) {       
            if ($currentUser->rol!=0) {

                $departamento_id=$currentUser->departamento_id;
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
                $departamentos = DB::select('select * from `departamentos`');
                 return response()->json(['productos'=>$stockdepartamentos, 'departamentos'=>$departamentos], 200);  
            }else if($currentUser->rol==0){
                $departamentos = DB::select('select * from `departamentos`');
                $productos=DB::select("SELECT id,nombre,codigo,stock,stock2,categoria_id,tipo_id,rubro_id,precio,stock_min,stock2_min FROM  `stock` WHERE 1 ");

                 return response()->json(['productos'=>$productos, 'departamentos'=>$departamentos], 200); 
            } 
         }else{        
             return response()->json(['error'=>'Usuario no encontrado.'], 404);        
         }     
       
     }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //cargar todos los productos en el stock
        /*$productos = \App\Stock::select('nombre', 'codigo', 'precio',
            'stock',
            'stock_min',  'categoria_id',
            'proveedor_id')->with('categoria')->get();

        if(count($productos) == 0){
            return response()->json(['error'=>'No existen productos en el stock.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'productos'=>$productos], 200);
        }*/


        $produc=DB::select("SELECT id,nombre,codigo,stock,stock2,categoria_id,tipo_id,rubro_id,precio,stock_min,stock2_min FROM  `stock` WHERE 1 ");

        $departs=DB::select("SELECT id,nombre FROM  `departamentos` WHERE 1 ");


        $categ=DB::select("SELECT id,nombre,codigo,tipo_id,rubro_id FROM  `categorias` WHERE 1 ");

        for ($i=0; $i < count($produc); $i++) { 
            $produc[$i]->categoria=[];
            for ($j=0; $j < count($categ); $j++) { 
                if ($produc[$i]->categoria_id==$categ[$j]->id) {
                    array_push($produc[$i]->categoria,$categ[$j]);
                }
            }

            $aux=DB::select("SELECT departamento_id 
                    FROM  `stock_permisos_departs`
                    WHERE stock_id = ".$produc[$i]->id);

            $produc[$i]->departamentos=[];

            for ($k=0; $k < count($aux); $k++) { 
                for ($h=0; $h < count($departs) ; $h++) { 
                    if ($aux[$k]->departamento_id == $departs[$h]->id) {
                        array_push($produc[$i]->departamentos, $departs[$h]);
                    }
                }
            }
        }
        

        if(count($produc) == 0){
            return response()->json(['error'=>'No existen productos en el stock.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'productos'=>$produc], 200);
        } 
    }

    public function index_centro_costos()
    {
        //cargar todos los productos en el stock
        /*$productos = \App\Stock::select('nombre', 'codigo', 'precio',
            'stock',
            'stock_min',  'categoria_id',
            'proveedor_id')->with('categoria')->get();

        if(count($productos) == 0){
            return response()->json(['error'=>'No existen productos en el stock.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'productos'=>$productos], 200);
        }*/


        $produc=DB::select("SELECT id,nombre,codigo,stock,stock2,categoria_id,tipo_id,rubro_id,precio,stock_min,stock2_min FROM  `stock` WHERE 1 ");

        $departs=DB::select("SELECT id,nombre FROM  `departamentos` WHERE 1 ");


        $categ=DB::select("SELECT id,nombre,codigo,tipo_id,rubro_id FROM  `categorias` WHERE 1 ");

        $centrocostos = \App\CentroCostos::with('contratos')->get();

        for ($i=0; $i < count($produc); $i++) { 
            $produc[$i]->categoria=[];
            for ($j=0; $j < count($categ); $j++) { 
                if ($produc[$i]->categoria_id==$categ[$j]->id) {
                    array_push($produc[$i]->categoria,$categ[$j]);
                }
            }

            $aux=DB::select("SELECT departamento_id 
                    FROM  `stock_permisos_departs`
                    WHERE stock_id = ".$produc[$i]->id);

            $produc[$i]->departamentos=[];

            for ($k=0; $k < count($aux); $k++) { 
                for ($h=0; $h < count($departs) ; $h++) { 
                    if ($aux[$k]->departamento_id == $departs[$h]->id) {
                        array_push($produc[$i]->departamentos, $departs[$h]);
                    }
                }
            }
        }


        if(count($produc) == 0){
            return response()->json(['error'=>'No existen productos en el stock.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'productos'=>$produc, 'centrocostos'=>$centrocostos], 200);
        } 
    }

     
    public function todos()
    {


        $produc=DB::select("SELECT id,nombre,codigo,stock,stock2,categoria_id,tipo_id,rubro_id,precio,stock_min,stock2_min FROM  `stock` WHERE 1 ");

        $departs=DB::select("SELECT id,nombre FROM  `departamentos` WHERE 1 ");


        $categ=DB::select("SELECT id,nombre,codigo,tipo_id,rubro_id FROM  `categorias` WHERE 1 ");

        for ($i=0; $i < count($produc); $i++) { 
            $produc[$i]->categoria=[];
            for ($j=0; $j < count($categ); $j++) { 
                if ($produc[$i]->categoria_id==$categ[$j]->id) {
                    array_push($produc[$i]->categoria,$categ[$j]);
                }
            }

            $aux=DB::select("SELECT departamento_id 
                    FROM  `stock_permisos_departs`
                    WHERE stock_id = ".$produc[$i]->id);

            $produc[$i]->departamentos=[];

            for ($k=0; $k < count($aux); $k++) { 
                for ($h=0; $h < count($departs) ; $h++) { 
                    if ($aux[$k]->departamento_id == $departs[$h]->id) {
                        array_push($produc[$i]->departamentos, $departs[$h]);
                    }
                }
            }
        }


        $tipos = \App\Tipo::all();
        $rubros = \App\Rubro::all();
        $categorias = \App\Categoria::with('tipo')->with('rubro')->get();
        
        if(count($produc) == 0){
            return response()->json(['error'=>'No existen productos en el stock.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 
                'productos'=>$produc,
                'tipos'=>$tipos,
                'rubros'=>$rubros,
                'categorias'=>$categorias,
                'departamentos'=>$departs
            ], 200);
        } 
    }


    public function indexCategoriasUso()
    {
        //cargar todos los productos en el stock
        //$productos = \App\Categoria::where('tipo_id',2)->with('stock')->get();
        $productos = \App\Stock::where('tipo_id',2)->get();

        if(count($productos) == 0){
            return response()->json(['error'=>'No existen productos en el stock Uso.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'productos'=>$productos], 200);
        } 
    }

    public function indexCategoriasConsumo()
    {
        //cargar todos los productos en el stock
       // $productos = \App\Categoria::where('tipo_id',1)->with('stock')->get();
        $productos = \App\Stock::where('tipo_id',1)->get();
        
        if(count($productos) == 0){
            return response()->json(['error'=>'No existen productos en el stock Comsumo. '], 404);          
        }else{
            return response()->json(['status'=>'ok', 'productos'=>$productos], 200);
        } 
    }

    public function indexCategoriasServicio()
    {
        //cargar todos los productos en el stock
        $productos = \App\Stock::where('tipo_id',4)->get();

        if(count($productos) == 0){
            return response()->json(['error'=>'No existen productos en el stock Servicios.'], 404);          
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
        /*if ( !$request->input('nombre') || !$request->input('precio') ||
             !$request->input('stock') || !$request->input('peps') 
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['error'=>'Faltan datos necesarios para el proceso de alta.'],422);
        } */

        $permisos_departs=$request->input('permisos_departs');

        // Comprobamos si la categoria que nos están pasando existe o no.
        $categoria = \App\Categoria::find($request->input('categoria_id'));
        if(count($categoria)==0){
            return response()->json(['error'=>'No existe la categoría con id '.$request->input('categoria_id')], 404);          
        } 
        
        $aux = \App\Stock::where('nombre', $request->input('nombre'))
            ->where('categoria_id', $categoria->id)->get();
        if(count($aux)!=0){
           // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Ya existe un producto con esas características en la categoria con id '.$categoria->id], 409);
        }

        if ($request->input('codigo')) {
            $aux2 = \App\Stock::where('codigo', $request->input('codigo'))->get();
            if(count($aux2)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe un producto con el código '.$request->input('codigo')], 409);
            }
        }

        if ($request->input('proveedor_id')) {
            $proveedor = \App\Proveedor::find($request->input('proveedor_id'));
            if(count($proveedor)==0){
                return response()->json(['error'=>'No existe el proveedor con id '.$request->input('proveedor_id')], 404);          
            }
        }

        

        //Creamos el producto
        $producto = \App\Stock::create($request->all());

        if ($permisos_departs) {
            $permisos_departs = json_decode($request->input('permisos_departs'));

            //Eliminar las relaciones(permisos) en la tabla pivote
            $producto->permisos_departs()->detach();

            //Agregar las nuevas relaciones(permisos) en la tabla pivote
            for ($i=0; $i < count($permisos_departs) ; $i++) { 
                  $producto->permisos_departs()->attach($permisos_departs[$i]->id);
            }

            $bandera=true; 
        }

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
        //cargar un producto del stock
        $producto = \App\Stock::with('categoria')->find($id);

        if(count($producto)==0){
            return response()->json(['error'=>'No existe el producto con id '.$id.' en el stock.'], 404);          
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
        // Comprobamos si el producto que nos están pasando existe o no en el stock.
        $producto = \App\Stock::find($id);

        if(count($producto)==0){
            return response()->json(['error'=>'No existe el producto con id '.$id.' en el stock.'], 404);          
        }   
         
        // Listado de campos recibidos teóricamente.
        $nombre=$request->input('nombre');
        $codigo=$request->input('codigo');
        $precio=$request->input('precio');
        $stock=$request->input('stock'); // num existencias
        $stock2=$request->input('stock2'); // num existencias
        $peps=$request->input('peps');
        $valor_reposicion=$request->input('valor_reposicion');
        $stock_min=$request->input('stock_min');
        $stock2_min=$request->input('stock2_min');
        $partida_parcial=$request->input('partida_parcial');
        $categoria_id=$request->input('categoria_id');
        $tipo_id=$request->input('tipo_id');
        $rubro_id=$request->input('rubro_id');
        $proveedor_id=$request->input('proveedor_id');
        $permisos_departs=$request->input('permisos_departs');
                
        // Creamos una bandera para controlar si se ha modificado algún dato.
        $bandera = false;
        if ($tipo_id != null && $tipo_id!='')
        {
            $producto->tipo_id = $tipo_id;
            $bandera=true;
            //return 1;
        }
        if ($rubro_id != null && $rubro_id!='')
        {
            $producto->rubro_id = $rubro_id;
            $bandera=true;
            //return 1;
        }
        if ($categoria_id != null && $categoria_id!='')
        {
            $producto->categoria_id = $categoria_id;
            $bandera=true;
        }
        // Actualización parcial de campos.
        if ($nombre != null && $nombre!='')
        {
            $aux = \App\Stock::where('nombre', $request->input('nombre'))
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
            $aux2 = \App\Stock::where('codigo', $request->input('codigo'))
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

        if ($stock2 != null && $stock2!='')
        {
            $producto->stock2 = $stock2;
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

        if ($stock2_min != null && $stock2_min!='')
        {
            $producto->stock2_min = $stock2_min;
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

        if ($permisos_departs) {
            $permisos_departs = json_decode($request->input('permisos_departs'));

            //Eliminar las relaciones(permisos) en la tabla pivote
            $producto->permisos_departs()->detach();

            //Agregar las nuevas relaciones(permisos) en la tabla pivote
            for ($i=0; $i < count($permisos_departs) ; $i++) { 
                  $producto->permisos_departs()->attach($permisos_departs[$i]->id);
            }

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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Comprobamos si el producto que nos están pasando existe o no en el stock.
        $producto = \App\Stock::find($id);

        if(count($producto)==0){
            return response()->json(['error'=>'No existe el producto con id '.$id.' en el stock.'], 404);          
        } 

        $solicitud = $producto->solicitud;

        if (sizeof($solicitud) > 0)
        {
            // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Este producto no puede ser eliminado porque posee pedidos asociados.'], 409);
        }

        // Eliminamos el producto si no tiene relaciones.
        $producto->delete();

        return response()->json(['status'=>'ok', 'message'=>'Se ha eliminado correctamente el producto del stock.'], 200);
    }
}
