<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// Activamos uso de caché.
use Illuminate\Support\Facades\Cache;

class PrestockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //cargar todas las cat
        $stock = \App\Prestock::all();
        //return response()->json(['stock'=>$stock], 200);
        //return count($categorias);

        for ($i=0; $i < count($stock); $i++) { 
            //$nuevo = new \App\Stock;
            $nuevo = new \App\Producto;
            $nuevo->nombre=$stock[$i]->nombre;
            /*if ($stock[$i]->codigo==0) {
            	$nuevo->codigo=$stock[$i]->codigo+10000+$i;
            }else{
            	$nuevo->codigo=$stock[$i]->codigo;
            }*/
            //$nuevo->precio=$stock[$i]->precio;
            //$nuevo->stock=$stock[$i]->cantidad;
            $nuevo->categoria_id=$stock[$i]->categoria_id;
            
            if($nuevo->save()){
                //return $this->response->created();
            }else{
                return $this->response->error($nuevo[$i]->nombre, 500);
            }
        }
        // if(count($categorias) == 0){
        //     return response()->json(['error'=>'No existen categorías.'], 404);          
        // }else{
        //     return response()->json(['categorias'=>$categorias], 200);
        // } 
        
    }

}
