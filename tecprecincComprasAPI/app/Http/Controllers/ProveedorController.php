<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //cargar todos los proveedores con los productos que ofrecen
        $proveedores = \App\Proveedor::with('productos')->get();
        $categorias = \App\Categoria::with('tipo')->with('rubro')->get();
        //return $proveedores;
        if(count($proveedores) == 0){
            return response()->json(['error'=>'No existen proveedores.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'proveedores'=>$proveedores, 'categorias'=>$categorias], 200);
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
        // Primero comprobaremos si estamos recibiendo todos los campos obligatorios.
       /* if (!$request->input('razonSocial') ||
            !$request->input('cuit') ||
            !$request->input('habilitado') ||
            !$request->input('estado')) 
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['error'=>'Faltan datos necesarios para el proceso de alta.'],422);
        }*/

        //validaciones
        $aux1 = \App\Proveedor::where('razon_social', $request->input('razon_social'))->get();
        if(count($aux1) != 0){
           // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Ya existe otro proveedor con el nombre(razón social) '.$request->input('razon_social')], 409);
        } 

        if ($request->input('nombre_fantacia')) {
            $aux2 = \App\Proveedor::where('nombre_fantacia', $request->input('nombre_fantacia'))->get();
            if(count($aux2) != 0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro proveedor con el nombre fantacia '.$request->input('nombre_fantacia')], 409);
            } 
        }

        $aux3 = \App\Proveedor::where('cuit', $request->input('cuit'))->get();
        if(count($aux3) != 0){
           // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Ya existe otro proveedor con el cuit '.$request->input('cuit')], 409);
        } 

        if ($request->input('email')) {
            $aux4 = \App\Proveedor::where('email', $request->input('email'))->get();
            if(count($aux4) != 0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro proveedor con el email '.$request->input('email')], 409);
            } 
        }

        if ($request->input('productos')) {
            //Verificar que todos los productos del pedido existen
            $productos = json_decode($request->input('productos'));
            for ($i=0; $i < count($productos) ; $i++) { 
                $aux5 = \App\Producto::find($productos[$i]->producto_id);
                if(count($aux5) == 0){
                   // Devolvemos un código 409 Conflict. 
                    return response()->json(['error'=>'No existe el producto con id '.$productos[$i]->id], 409);
                }   
            } 
        }

        if ($request->input('categorias')) {
            //Verificar que todas las categorias existen
            $categorias = json_decode($request->input('categorias'));
            for ($i=0; $i < count($categorias) ; $i++) { 
                $aux6 = \App\Categoria::find($categorias[$i]->id);
                if(count($aux6) == 0){
                   // Devolvemos un código 409 Conflict. 
                    return response()->json(['error'=>'No existe la categoria con id '.$categorias[$i]->id], 409);
                }   
            } 
        }

        $nuevoProveedor=new \App\Proveedor;
        //$nuevoProveedor=$request->all();
        $nuevoProveedor->razon_social=$request->input('razon_social');
        $nuevoProveedor->nombre_fantacia=$request->input('nombre_fantacia');
        $nuevoProveedor->cuit=$request->input('cuit');
        $nuevoProveedor->telefono=$request->input('telefono');
        $nuevoProveedor->fax=$request->input('fax');
        $nuevoProveedor->email=$request->input('email');
        $nuevoProveedor->habilitado=$request->input('habilitado');
        $nuevoProveedor->estado=$request->input('estado');
        $nuevoProveedor->calificacion=$request->input('calificacion');
        $nuevoProveedor->direccion=$request->input('direccion');
        $nuevoProveedor->forma_pago=$request->input('forma_pago'); 
        $nuevoProveedor->calle=$request->input('calle');
        $nuevoProveedor->nro=$request->input('nro');
        $nuevoProveedor->ciudad=$request->input('ciudad');
        $nuevoProveedor->provincia=$request->input('provincia');
        $nuevoProveedor->pais=$request->input('pais');
        if( $nuevoProveedor->save() ){

            if ($request->input('productos')) {
                //Crear las relaciones en la tabla pivote
                for ($i=0; $i < count($productos) ; $i++) { 

                    $nuevoProveedor->productos()->attach($productos[$i]->producto_id, ['precio' => $productos[$i]->precio]);
                       
                }
            }

            if ($request->input('categorias')) {
                //Crear las relaciones en la tabla pivote
                for ($i=0; $i < count($categorias) ; $i++) { 

                    $nuevoProveedor->categorias()->attach($categorias[$i]->id);
                       
                }
            }

            
            return response()->json(['status'=>'ok', 'proveedor'=>$nuevoProveedor], 200);
        }else{
            return response()->json(['error'=>'Error al crear el proveedor.'], 500);
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
        //cargar un proveedor
        $proveedor = \App\Proveedor::with('productos')->find($id);

        if(count($proveedor)==0){
            return response()->json(['error'=>'No existe el proveedor con id '.$id], 404);          
        }else{

            //$proveedor->productos = $proveedor->productos;
            return response()->json(['status'=>'ok', 'proveedor'=>$proveedor], 200);
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
        // Comprobamos si el proveedor que nos están pasando existe o no.
        $proveedor=\App\Proveedor::find($id);

        if (count($proveedor)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el proveedor con id '.$id], 404);
        }      

        // Listado de campos recibidos teóricamente.
        $razon_social=$request->input('razon_social'); 
        $nombreFantacia=$request->input('nombre_fantacia'); 
        $cuit=$request->input('cuit'); 
        $telefono=$request->input('telefono');
        $fax=$request->input('fax');
        $email=$request->input('email');
        $habilitado=$request->input('habilitado');
        $estado=$request->input('estado');
        $calificacion=$request->input('calificacion');
        $productos=$request->input('productos');
        $motivo=$request->input('motivo');      
        $direccion=$request->input('direccion');      
        $forma_pago=$request->input('forma_pago');
        $categorias=$request->input('categorias');
        $calle=$request->input('calle');
        $nro=$request->input('nro');
        $ciudad=$request->input('ciudad');
        $provincia=$request->input('provincia');
        $pais=$request->input('pais');

        // Creamos una bandera para controlar si se ha modificado algún dato.
        $bandera = false;

        // Actualización parcial de campos.
        if ($razon_social != null && $razon_social!='')
        {
            $aux1 = \App\Proveedor::where('razon_social', $request->input('razon_social'))
                ->where('id', '<>', $proveedor->id)->get();
            if(count($aux1) != 0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro proveedor con el nombre(razón social) '.$request->input('razon_social')], 409);
            } 

            $proveedor->razon_social = $razon_social;
            $bandera=true;
        }

        if ($nombreFantacia != null && $nombreFantacia!='')
        {
            $aux1 = \App\Proveedor::where('nombreFantacia', $request->input('nombreFantacia'))
                ->where('id', '<>', $proveedor->id)->get();
            if(count($aux1) != 0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro proveedor con el nombre fantacia '.$request->input('nombreFantacia')], 409);
            } 

            $proveedor->nombreFantacia = $nombreFantacia;
            $bandera=true;
        }

        if ($cuit != null && $cuit!='')
        {
            $aux1 = \App\Proveedor::where('cuit', $request->input('cuit'))
                ->where('id', '<>', $proveedor->id)->get();
            if(count($aux1) != 0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro proveedor con el cuit '.$request->input('cuit')], 409);
            } 

            $proveedor->cuit = $cuit;
            $bandera=true;
        }

        if ($telefono != null && $telefono!='')
        {
            $proveedor->telefono = $telefono;
            $bandera=true;
        }

        if ($fax != null && $fax!='')
        {
            $proveedor->fax = $fax;
            $bandera=true;
        }

        if ($email != null && $email!='')
        {
            $aux1 = \App\Proveedor::where('email', $request->input('email'))
                ->where('id', '<>', $proveedor->id)->get();
            if(count($aux1) != 0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro proveedor con el email '.$request->input('email')], 409);
            } 

            $proveedor->email = $email;
            $bandera=true;
        }

        if ($habilitado != null && $habilitado!='')
        {
            $proveedor->habilitado = $habilitado;
            $bandera=true;
        }

        if ($estado != null && $estado!='')
        {
            $proveedor->estado = $estado;
            $bandera=true;
        }

        if ($calificacion != null && $calificacion!='')
        {
            $proveedor->calificacion = $calificacion;
            $bandera=true;
        }

        if ($productos != null && $productos!='')
        {
            //Eliminar las relaciones(productos) en la tabla pivote
            $proveedor->productos()->detach();

            //Crear las nuevas relaciones en la tabla pivote
            //return $request->input('productos');
            $productos = json_decode($request->input('productos'));
            
            for ($i=0; $i < count($productos) ; $i++) { 

                //$proveedor->productos()->attach($productos[$i]->producto_id, ['precio' => $productos[$i]->precio]);

                $proveedor->productos()->attach($productos[$i]->id);       
            }
            
            $bandera=true;
        }
        if ($motivo != null && $motivo!='')     
         {     
             $proveedor->motivo = $motivo;     
             $bandera=true;        
         }     
       
         if ($direccion != null && $direccion!='')     
         {     
             $proveedor->direccion = $direccion;       
             $bandera=true;        
         }//'calle','nro','ciudad','provincia','pais' 

         if ($calle != null && $calle!='')     
         {     
             $proveedor->calle = $calle;       
             $bandera=true;        
         }    
         if ($nro != null && $nro!='')     
         {     
             $proveedor->nro = $nro;       
             $bandera=true;        
         }

         if ($ciudad != null && $ciudad!='')     
         {     
             $proveedor->ciudad = $ciudad;       
             $bandera=true;        
         }

         if ($provincia != null && $provincia!='')     
         {     
             $proveedor->provincia = $provincia;       
             $bandera=true;        
         }

         if ($pais != null && $pais!='')     
         {     
             $proveedor->pais = $pais;       
             $bandera=true;        
         }    
       
         if ($forma_pago != null && $forma_pago!='')       
         {     
             $proveedor->forma_pago = $forma_pago;     
             $bandera=true;        
         }

         if ($categorias != null && $categorias!='')
        {
            //Eliminar las relaciones(categorias) en la tabla pivote
            $proveedor->categorias()->detach();

            //Crear las nuevas relaciones en la tabla pivote
            $categorias = json_decode($request->input('categorias'));
            
            for ($i=0; $i < count($categorias) ; $i++) { 

                $proveedor->categorias()->attach($categorias[$i]->id);       
            }
            
            $bandera=true;
        }

        if ($bandera)
        {
            // Almacenamos en la base de datos el registro.
            if ($proveedor->save()) {
                return response()->json(['status'=>'ok','message'=>'Proveedor editado con éxito.', 'proveedor'=>$proveedor], 200);
            }else{
                return response()->json(['error'=>'Error al actualizar el proveedor.'], 500);
            }
            
        }
        else
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
            // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
            return response()->json(['error'=>'No se ha modificado ningún dato al proveedor.'],409);
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
        // Comprobamos si el proveedor que nos están pasando existe o no.
        $proveedor=\App\Proveedor::find($id);

        if (count($proveedor)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el proveedor con id '.$id], 404);
        } 
       
        //Eliminar las relaciones(productos) en la tabla pivote
        $proveedor->productos()->detach();

        // Eliminamos el proveedor.
        $proveedor->delete();

        return response()->json(['status'=>'ok', 'message'=>'Se ha eliminado correctamente el proveedor.'], 200);
    }

    /*Retorna un proveedor id con sus categorias asociadas*/
    public function proveedorCats($id)
    {
        //cargar un proveedor
        $proveedor = \App\Proveedor::with('categorias.productos')->find($id);

        if(count($proveedor)==0){
            return response()->json(['error'=>'No existe el proveedor con id '.$id], 404);          
        }else{

            //$proveedor->productos = $proveedor->productos;
            return response()->json(['proveedor'=>$proveedor], 200);
        }
    }
}
