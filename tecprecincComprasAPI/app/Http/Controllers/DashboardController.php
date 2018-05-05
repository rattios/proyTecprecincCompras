<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dia_actual = date("d"); //j  Día del mes sin ceros iniciales 1 a 31
                                //d Día del mes, 2 dígitos con ceros iniciales  01 a 31
        $mes_actual = date("m");
        $anio_actual = date("Y");

        //Grafico de lineas----<

        $labelsPedidosMesActual = [];
        if ($dia_actual <= 10) {
            for ($i=1; $i <= 10; $i++) { 
                array_push($labelsPedidosMesActual, $i);
            }
        }else{
            for ($i=1; $i <= $dia_actual; $i++) { 
                array_push($labelsPedidosMesActual, $i);
            }
        }

        $pedidosMesActual = [];
        for ($i=1; $i <= $dia_actual; $i++) { 
            $pedidosDiaX = \App\Pedido::select(DB::raw('count(*) as contador'))
            ->where(DB::raw('DAY(created_at)'),$i)
            ->where(DB::raw('MONTH(created_at)'),DB::raw('MONTH(now())'))
            ->where(DB::raw('YEAR(created_at)'),DB::raw('YEAR(now())'))
            ->get();

            array_push($pedidosMesActual, $pedidosDiaX[0]);
        }

        $clientesMesActual = [];
        for ($i=1; $i <= $dia_actual; $i++) { 
            $clientesDiaX = \App\User::select(DB::raw('count(*) as contador'))
            ->where('tipo',1)
            ->where(DB::raw('DAY(created_at)'),$i)
            ->where(DB::raw('MONTH(created_at)'),DB::raw('MONTH(now())'))
            ->where(DB::raw('YEAR(created_at)'),DB::raw('YEAR(now())'))
            ->get();

            array_push($clientesMesActual, $clientesDiaX[0]);
        }

        $maxEjeY = 5;
        $totalPedidosMes = 0;
        $totalClientesMes = 0;

        for ($i=0; $i < $dia_actual; $i++) { 
            if ($pedidosMesActual[$i]->contador > $maxEjeY) {
                $maxEjeY = $pedidosMesActual[$i]->contador;
            }
            if ($clientesMesActual[$i]->contador > $maxEjeY) {
                $maxEjeY = $clientesMesActual[$i]->contador;
            }
            $totalPedidosMes = $totalPedidosMes + $pedidosMesActual[$i]->contador;
            $totalClientesMes = $totalClientesMes + $clientesMesActual[$i]->contador;
        }

        //Grafico de lineas---->

        $contClientesHoy = \App\User::select(DB::raw('count(*) as contador'))
            ->where('tipo',1)
            ->where(DB::raw('DAY(created_at)'),$dia_actual)
            ->where(DB::raw('MONTH(created_at)'),DB::raw('MONTH(now())'))
            ->where(DB::raw('YEAR(created_at)'),DB::raw('YEAR(now())'))
            ->get();

        $contClientesHoy = $contClientesHoy[0]->contador;

        $pedidosHoy = \App\Pedido::with('productos')
            ->where(DB::raw('DAY(created_at)'),$dia_actual)
            ->where(DB::raw('MONTH(created_at)'),DB::raw('MONTH(now())'))
            ->where(DB::raw('YEAR(created_at)'),DB::raw('YEAR(now())'))
            ->get();

        $contPedidosHoy = count($pedidosHoy); 

        $prodSolicitadosHoy = 0;
        $dineroRecaudadoHoy = 0;

        if(count($pedidosHoy) > 0){
            for ($i=0; $i < count($pedidosHoy) ; $i++) { 
                /*for ($j=0; $j < count($pedidosHoy[$i]->productos); $j++) { 
                    $prodSolicitadosHoy = $prodSolicitadosHoy + $pedidosHoy[$i]->productos[$j]->pivot->cantidad;
                }*/
                $prodSolicitadosHoy = $prodSolicitadosHoy + count($pedidosHoy[$i]->productos);
                $dineroRecaudadoHoy = $dineroRecaudadoHoy + $pedidosHoy[$i]->total;
            }
        }


        return response()->json(['status'=>'ok',
            'labelsPedidosMesActual'=>$labelsPedidosMesActual,
            'pedidosMesActual'=>$pedidosMesActual,
            'clientesMesActual'=>$clientesMesActual,
            'maxEjeY'=>$maxEjeY,
            'totalPedidosMes'=>$totalPedidosMes,
            'totalClientesMes'=>$totalClientesMes,
            'contClientesHoy'=>$contClientesHoy,
            'contPedidosHoy'=>$contPedidosHoy,
            'prodSolicitadosHoy'=>$prodSolicitadosHoy,
            'dineroRecaudadoHoy'=>$dineroRecaudadoHoy
                ], 200);

        
    }

    public function dashboard(Request $request)
    {
        $usuarios = DB::select("SELECT `id`,`departamento_id`, `nombre`,`rol` FROM `usuarios` WHERE 1");
        $departamentos = DB::select("SELECT `id`,`nombre` FROM `departamentos` WHERE 1");
        $pedidos = DB::select("SELECT `estado`, `usuario_id`, `created_at` FROM `pedidos` WHERE 1");
        $ultimosPedidos = DB::select("SELECT * FROM `pedidos` ORDER BY `pedidos`.`id` DESC LIMIT 10");
        $proveedores = DB::select("SELECT `id`,`razon_social` FROM `proveedores` WHERE 1");
        $productos = DB::select("SELECT `nombre` FROM `productos` WHERE 1");
        $stocks = DB::select("SELECT `nombre`,`stock` FROM `stock` WHERE 1");
        $stockdepartamentos = DB::select("SELECT `stock`,`departamento_id`  FROM `stockdepartamentos` WHERE 1");
        $centro_costos = DB::select("SELECT `descripcion` FROM `centro_costos` WHERE 1");
        $contratos = DB::select("SELECT `nombre` FROM `contratos` WHERE 1");
        
        
        $pedidoDepartamentos=[];
        for ($k=0; $k < count($departamentos); $k++) { 
            $departamentos[$k]->countPedidos=0;
            for ($i=0; $i < count($usuarios); $i++) {
                for ($j=0; $j < count($pedidos); $j++) { 
                    if ($usuarios[$i]->id==$pedidos[$j]->usuario_id && $departamentos[$k]->id==$usuarios[$i]->departamento_id) {
                        $departamentos[$k]->countPedidos=$departamentos[$k]->countPedidos+1;
                    }
                } 
            }           
        }
        for ($i=0; $i < count($departamentos); $i++) { 
            $departamentos[$i]->countProductos=0;
            for ($j=0; $j < count($stockdepartamentos); $j++) { 
                if ($departamentos[$i]->id==$stockdepartamentos[$j]->departamento_id) {
                    $departamentos[$i]->countProductos=$departamentos[$i]->countProductos+$stockdepartamentos[$j]->stock;
                }
            }
        }

        $countStocks=0;
        for ($i=0; $i < count($stocks); $i++) { 
          $countStocks=$countStocks+ $stocks[$i]->stock;
        }
        return response()->json(['status'=>'ok',
            'usuarios'=>count($usuarios),
            'departamentos'=>$departamentos,
            'proveedores'=>count($proveedores),
            'productos'=>count($productos),
            'stocks'=>$countStocks,
            'stockdepartamentos'=>count($stockdepartamentos),
            'centro_costos'=>count($centro_costos),
            'contratos'=>count($contratos),
            'countpedidos'=>count($pedidos),
            'pedidos'=>$pedidos,
            'ultimosPedidos'=>$ultimosPedidos
                ], 200);

        
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
