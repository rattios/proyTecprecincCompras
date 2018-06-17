<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\DB;
use Hash;
use DB;

// Activamos uso de caché.
use Illuminate\Support\Facades\Cache;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //DB::enableQueryLog();

        //cargar todos los usuarios con su departamento
        //Consulta Eloquent
        
        
        //Consulta Raw SQL
        //$usuarios = DB::select('select * from `usuarios`');

        /*$usuarios = DB::select('select u.*, d.nombre as dep_nombre, d.codigo, d.id as dep_id from `usuarios` as u LEFT JOIN departamentos AS d ON ( u.departamento_id = d.id )');*/

        //$log = DB::getQueryLog();
        //var_dump($log);
        //print_r($log);
        //$lastQuery = end($log);

        // Activamos la caché de los resultados.
        //  Cache::remember('tabla', $minutes, function()
        /*$usuarios=Cache::remember('usuarios',20/60, function()
        {
            // Caché válida durante 20 segundos.
            return \App\User::with('departamento')->get();
        });*/

        //Borrar elemento de la cache
        //Cache::forget('usuarios');
        //Borra todo el cache
        //Cache::flush();

        //Si la variable no existe, la creamos
        /*if (!Cache::has('usuarios')) {
             $usuarios = \App\User::with('departamento')->get();
             Cache::put('usuarios', $usuarios, 1);
             return response()->json(['status'=>'sin cache', 'usuarios'=>$usuarios], 200);
         }else{
            $usuarios = Cache::get('usuarios');
            return response()->json(['status'=>'con cache', 'usuarios'=>$usuarios], 200);
         }*/
         return 1;
         $usuarios = \App\User::with('departamento')->get();
         return $usuarios;
            return response()->json(['status'=>'ok', 'usuarios'=>$usuarios], 200);

        if ($request->input('departamento_id')==1) {
            $usuarios = \App\User::where('departamento_id',1)->with('departamento')->get();
        }if ($request->input('departamento_id')==2) {
            $usuarios = \App\User::where('departamento_id',2)->with('departamento')->get();
        }if ($request->input('departamento_id')==3) {
            $usuarios = \App\User::where('departamento_id',3)->with('departamento')->get();
        }if ($request->input('departamento_id')==4) {
            $usuarios = \App\User::where('departamento_id',4)->with('departamento')->get();
        }if ($request->input('departamento_id')==5) {
            $usuarios = \App\User::where('departamento_id',5)->with('departamento')->get();
            return response()->json(['status'=>'ok', 'usuarios'=>$usuarios], 200);
        }if ($request->input('departamento_id')==6) {
            $usuarios = \App\User::where('departamento_id',6)->with('departamento')->get();
        }if ($request->input('departamento_id')==7) {
            $usuarios = \App\User::where('departamento_id',7)->with('departamento')->get();
        }else{
            $usuarios = \App\User::with('departamento')->get();
        }
        

        if(count($usuarios) == 0){
            return response()->json(['error'=>'No existen usuarios.'], 404);          
        }else{

            /*for ($i=0; $i < count($usuarios); $i++) { 
                $usuarios[$i]->departamento = DB::select('select * from `departamentos` where `departamentos`.`id` in (?)', [$usuarios[$i]->departamento_id]);
            }*/

            //$log = DB::getQueryLog();

            return response()->json(['status'=>'ok', 'usuarios'=>$usuarios], 200);
        } 
    }

    public function usuariosPedidos()
    {
        //cargar todos los usuarios con sus pedidos
        $usuarios = \App\User::with('departamento')->with('pedidos')->get();

        if(count($usuarios) == 0){
            return response()->json(['error'=>'No existen usuarios.'], 404);          
        }else{
            return response()->json(['status'=>'ok', 'usuarios'=>$usuarios], 200);
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
        /*if ( !$request->input('user') || !$request->input('password') ||
            !$request->input('email') || !$request->input('nombre') ||
            !$request->input('apellido') || !$request->input('telefono') ||
            !$request->input('departamento_id') || !$request->input('rol'))
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['error'=>'Faltan datos necesarios para el proceso de alta.'],422);
        } */
        
        $aux = \App\User::where('user', $request->input('user'))
            ->orWhere('email', $request->input('email'))->get();
        if(count($aux)!=0){
           // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Ya existe un usuario con este email.'], 409);
        }

        $aux2 = \App\Departamento::find($request->input('departamento_id'));
        if(count($aux2)==0){
           // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'No existe el departamento que se quiere asociar al usuario.'], 409);
        }

        /*Primero creo una instancia en la tabla usuarios*/
        $usuario = new \App\User;
        $usuario->user = $request->input('user');
        $usuario->password = Hash::make($request->input('password'));
        $usuario->email = $request->input('email');
        $usuario->nombre = $request->input('nombre');
        $usuario->apellido = $request->input('apellido');
        $usuario->telefono = $request->input('telefono');
        $usuario->departamento_id = $request->input('departamento_id');
        $usuario->rol = $request->input('rol');

        if($usuario->save()){
           return response()->json(['status'=>'ok', 'usuario'=>$usuario], 200);
        }else{
            return response()->json(['error'=>'Error al crear el usuario.'], 500);
        }

        /*$request->password = Hash::make($request->input('password'));

        if($nuevoUsuario=\App\User::create($request->all())){
           return response()->json(['status'=>'ok', 'usuario'=>$nuevoUsuario], 200);
        }else{
            return response()->json(['error'=>'Error al crear el usuario.'], 500);
        } */
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //cargar un usuario
        $usuario = \App\User::with('departamento')->find($id);

        if(count($usuario)==0){
            return response()->json(['error'=>'No existe el usuario con id '.$id], 404);          
        }else{

            return response()->json(['status'=>'ok', 'usuario'=>$usuario], 200);
        }
    }

    public function usuarioPedidos($id)
    {
        //cargar un usuario
        $usuario = \App\User::with('departamento')->with('pedidos')->find($id);
        //$usuario = \App\User::where('id',$id)->with('pedidos')->get();

        if(count($usuario)==0){
            return response()->json(['error'=>'No existe el usuario con id '.$id], 404);          
        }else{

            //cargar los pedidos del usuario
            //$usuario->pedidos = $usuario->pedidos()->get();

            return response()->json(['status'=>'ok', 'usuario'=>$usuario], 200);
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
        // Comprobamos si el usuario que nos están pasando existe o no.
        $usuario=\App\User::find($id);

        if (count($usuario)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el usuario con id '.$id], 404);
        }      

        // Listado de campos recibidos teóricamente.
        $user=$request->input('user'); 
        $password=$request->input('password'); 
        $email=$request->input('email'); 
        $nombre=$request->input('nombre');
        $apellido=$request->input('apellido');
        $telefono=$request->input('telefono');
        $rol=$request->input('rol');

        // Creamos una bandera para controlar si se ha modificado algún dato.
        $bandera = false;

        // Actualización parcial de campos.
        if ($user != null && $user!='')
        {
            $aux = \App\User::where('user', $request->input('user'))
            ->where('id', '<>', $usuario->id)->get();

            if(count($aux)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro usuario con ese user.'], 409);
            }

            $usuario->user = $user;
            $bandera=true;
        }

        if ($password != null && $password!='')
        {
            $usuario->password = Hash::make($request->input('password'));
            $bandera=true;
        }

        if ($email != null && $email!='')
        {
            $aux = \App\User::where('email', $request->input('email'))
            ->where('id', '<>', $usuario->id)->get();

            if(count($aux)!=0){
               // Devolvemos un código 409 Conflict. 
                return response()->json(['error'=>'Ya existe otro usuario con ese email.'], 409);
            }

            $usuario->email = $email;
            $bandera=true;
        }

        if ($nombre != null && $nombre!='')
        {
            $usuario->nombre = $nombre;
            $bandera=true;
        }

        if ($apellido != null && $apellido!='')
        {
            $usuario->apellido = $apellido;
            $bandera=true;
        }

        if ($telefono != null && $telefono!='')
        {
            $usuario->telefono = $telefono;
            $bandera=true;
        }

        if ($rol != null && $rol!='')
        {
            $usuario->rol = $rol;
            $bandera=true;
        }

        if ($bandera)
        {
            // Almacenamos en la base de datos el registro.
            if ($usuario->save()) {
                return response()->json(['status'=>'ok','usuario'=>$usuario], 200);
            }else{
                return response()->json(['error'=>'Error al actualizar el usuario.'], 500);
            }
            
        }
        else
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
            // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
            return response()->json(['error'=>'No se ha modificado ningún dato del usuario.'],409);
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
        // Comprobamos si el usuario que nos están pasando existe o no.
        $usuario=\App\User::find($id);

        if (count($usuario)==0)
        {
            // Devolvemos error codigo http 404
            return response()->json(['error'=>'No existe el usuario con id '.$id], 404);
        }

        $pedidos = $usuario->pedidos;

        if (sizeof($pedidos) > 0)
        {
            /*for ($i=0; $i < count($pedidos); $i++) { 
                //Eliminar las relaciones(productos) en la tabla pivote
                $pedidos[$i]->solicitud()->detach();

                // Eliminamos el pedido.
                $pedidos[$i]->delete();
            }*/

            // Devolvemos un código 409 Conflict. 
            return response()->json(['error'=>'Este usuario no puede ser eliminado.'], 409);
        }

        // Eliminamos el usuario.
        $usuario->delete();

        return response()->json(['status'=>'ok', 'message'=>'Se ha eliminado correctamente el usuario.'], 200);
    }
}
