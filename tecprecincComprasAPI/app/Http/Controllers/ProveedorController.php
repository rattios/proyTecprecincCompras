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

        if(count($proveedores) == 0){
            return response()->json(['error'=>'No existen proveedores.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'proveedores'=>$proveedores], 200);
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
        if (!$request->input('razonSocial') ||
            !$request->input('cuit') ||
            !$request->input('habilitado') ||
            !$request->input('estado')) 
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['error'=>'Faltan datos necesarios para el proceso de alta.'],422);
        }

        //validaciones
        $aux1 = \App\Proveedor::where('razonSocial', $request->input('razonSocial'))->get();
        if(count($aux1) != 0){
           // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Ya existe otro proveedor con el nombre(razón social) '.$request->input('razonSocial')], 409);
        } 

        if ($request->input('nombreFantacia')) {
            $aux2 = \App\Proveedor::where('nombreFantacia', $request->input('nombreFantacia'))->get();
            if(count($aux2) != 0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro proveedor con el nombre fantacia '.$request->input('nombreFantacia')], 409);
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
           

        if($nuevoProveedor=\App\Proveedor::create($request->all())){

            if ($request->input('productos')) {
                //Crear las relaciones en la tabla pivote
                for ($i=0; $i < count($productos) ; $i++) { 

                    $nuevoProveedor->productos()->attach($productos[$i]->producto_id, ['precio' => $productos[$i]->precio]);
                       
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
        $razonSocial=$request->input('razonSocial'); 
        $nombreFantacia=$request->input('nombreFantacia'); 
        $cuit=$request->input('cuit'); 
        $telefono=$request->input('telefono');
        $fax=$request->input('fax');
        $email=$request->input('email');
        $habilitado=$request->input('habilitado');
        $estado=$request->input('estado');
        $calificacion=$request->input('calificacion');
        $productos=$request->input('productos');

        // Creamos una bandera para controlar si se ha modificado algún dato.
        $bandera = false;

        // Actualización parcial de campos.
        if ($razonSocial != null && $razonSocial!='')
        {
            $aux1 = \App\Proveedor::where('razonSocial', $request->input('razonSocial'))
                ->where('id', '<>', $proveedor->id)->get();
            if(count($aux1) != 0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro proveedor con el nombre(razón social) '.$request->input('razonSocial')], 409);
            } 

            $proveedor->razonSocial = $razonSocial;
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
            $productos = json_decode($request->input('productos'));
            
            for ($i=0; $i < count($productos) ; $i++) { 

                $proveedor->productos()->attach($productos[$i]->producto_id, ['precio' => $productos[$i]->precio]);      
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
}
