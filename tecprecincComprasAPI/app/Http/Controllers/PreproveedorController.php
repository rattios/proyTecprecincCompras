<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// Activamos uso de caché.
use Illuminate\Support\Facades\Cache;

class PreproveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //cargar todas las cat
        $categorias = \App\Preproveedor::all();
        //return count($categorias);
        //return $categorias;
        for ($i=0; $i < count($categorias); $i++) { 
            $nuevaCategoria = new \App\Proveedor;
            $nuevaCategoria->id=$categorias[$i]->id;
            $nuevaCategoria->razon_social=$categorias[$i]->razon_social;
            $nuevaCategoria->nombre_fantacia=$categorias[$i]->nombre_fantacia;
            $nuevaCategoria->cuit=$categorias[$i]->cuit;
            $nuevaCategoria->telefono=$categorias[$i]->telefono;
            $nuevaCategoria->fax=$categorias[$i]->fax;
            $nuevaCategoria->email=$categorias[$i]->email;
            $nuevaCategoria->habilitado=$categorias[$i]->habilitado;
            $nuevaCategoria->estado=$categorias[$i]->estado;
            
            if($nuevaCategoria->save()){
                //return $this->response->created();
            }else{
                return $this->response->error($categorias[$i]->codigo, 500);
            }
        }
        // if(count($categorias) == 0){
        //     return response()->json(['error'=>'No existen categorías.'], 404);          
        // }else{
        //     return response()->json(['categorias'=>$categorias], 200);
        // } 
        
    }

}
