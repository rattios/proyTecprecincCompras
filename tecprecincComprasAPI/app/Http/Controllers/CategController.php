<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// Activamos uso de caché.
use Illuminate\Support\Facades\Cache;

class CategController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //cargar todas las cat
        $categorias = \App\Categ::all();
        //return count($categorias);

        for ($i=0; $i < count($categorias); $i++) { 
            $nuevaCategoria = new \App\Categoria;
            $nuevaCategoria->nombre=$categorias[$i]->nombre;
            $nuevaCategoria->codigo=$categorias[$i]->codigo;
            
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
