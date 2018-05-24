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

    public function fechaIgual($aComparar, $arreglo)
    {
        for ($i=0; $i < count($arreglo); $i++) { 
            if ($arreglo[$i]==$aComparar) {

                return false;
            }
        }
        return true;
    }
    public function dashboard(Request $request)
    {
        $usuarios = DB::select("SELECT `id`,`departamento_id`, `nombre`,`rol` FROM `usuarios` WHERE 1");
        $departamentos = DB::select("SELECT `id`,`nombre` FROM `departamentos` WHERE 1");
        $pedidos = DB::select("SELECT `estado`, `usuario_id`, `created_at`, `aprobar` FROM `pedidos` WHERE 1");
        $transferencias=DB::select("SELECT `id`,`created_at` FROM `transferencias` WHERE `estado`=2");
        $ultimosTransferencias=DB::select("SELECT `id`,`created_at` FROM `transferencias` WHERE `estado`=2 ORDER BY `id` DESC LIMIT 10");
        $ultimosPedidos = DB::select("SELECT * FROM `pedidos` ORDER BY `pedidos`.`id` DESC LIMIT 15");
        $proveedores = DB::select("SELECT `id`,`razon_social` FROM `proveedores` WHERE 1");
        $productos = DB::select("SELECT `nombre` FROM `productos` WHERE 1");
        $stocks = DB::select("SELECT `nombre`,`stock` FROM `stock` WHERE 1");
        $stockdepartamentos = DB::select("SELECT `stock`,`departamento_id`  FROM `stockdepartamentos` WHERE 1");
        $centro_costos = DB::select("SELECT `descripcion` FROM `centro_costos` WHERE 1");
        $contratos = DB::select("SELECT `nombre` FROM `contratos` WHERE 1");
        $eje=[];
        $ejeX=[];
        $ejeY=[];
        $ejeXt=[];
        $ejeYt=[];
        for ($i=0; $i < count($ultimosPedidos); $i++) { 
            $ultimosPedidos[$i]->usuario=[];
            $ultimosPedidos[$i]->departamento=[];
            $ultimosPedidos[$i]->cantidad=0;
            $ultimosPedidos[$i]->centro_costos='';
            
            for ($j=0; $j < count($usuarios); $j++) { 
                if ($ultimosPedidos[$i]->usuario_id==$usuarios[$j]->id) {
                    $ultimosPedidos[$i]->usuario=$usuarios[$j];
                    for ($k=0; $k < count($departamentos); $k++) { 
                        if ($usuarios[$j]->departamento_id==$departamentos[$k]->id) {
                            $ultimosPedidos[$i]->departamento=$departamentos[$k];
                        }
                    }
                }
            }
            $ps = DB::select("SELECT `cantidad` FROM `pedido_stock` WHERE `pedido_id`=".$ultimosPedidos[$i]->id);
            for ($cs=0; $cs < count($ps); $cs++) { 
                $ultimosPedidos[$i]->cantidad=$ultimosPedidos[$i]->cantidad+$ps[$cs]->cantidad;
            }
            $cc = DB::select("SELECT `descripcion` FROM `centro_costos` WHERE `id`=".$ultimosPedidos[$i]->centro_costos_id);
            $ultimosPedidos[$i]->centro_costos=$cc[0];
            
            $f=substr($ultimosPedidos[$i]->created_at, 5,5);
            if ($this->fechaIgual($f,$ejeX)) {
                array_push($ejeX,$f);
            }
            
        }
        for ($i=0; $i < count($ultimosTransferencias); $i++) { 
            $f=substr($ultimosTransferencias[$i]->created_at, 5,5);
            if ($this->fechaIgual($f,$ejeXt)) {
                array_push($ejeXt,$f);
            }
        }
        
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

        $estado0=0;
        $estado1=0;
        $estado2=0;
        $estado4=0;
        for ($i=0; $i < count($pedidos); $i++) { 
            if ($pedidos[$i]->estado==0 && $pedidos[$i]->aprobar==1) {
                $estado0++;
            }else if ($pedidos[$i]->estado==1) {
                $estado1++;
            }else if ($pedidos[$i]->estado==2) {
                $estado2++;
            }else if ($pedidos[$i]->estado==4) {
                $estado4++;
            }
        }

        $ejeX=array_reverse($ejeX);
        $cantidadP=0;
        for ($i=0; $i < count($ejeX); $i++) { 
            for ($j=0; $j < count($ultimosPedidos); $j++) { 
                if (substr($ultimosPedidos[$j]->created_at, 5,5)==$ejeX[$i]) {
                    $cantidadP=$cantidadP+1;
                }
            }
             array_push($ejeY,$cantidadP);
            //array_push($eje,array('ejeX' => $ejeX[$i],'ejeY' => $cantidad));
            $cantidadP=0;
        }

        $ejeXt=array_reverse($ejeXt);
        $cantidadP=0;
        for ($i=0; $i < count($ejeXt); $i++) { 
            for ($j=0; $j < count($ultimosTransferencias); $j++) { 
                if (substr($ultimosTransferencias[$j]->created_at, 5,5)==$ejeXt[$i]) {
                    $cantidadP=$cantidadP+1;
                }
            }
             array_push($ejeYt,$cantidadP);
            //array_push($eje,array('ejeX' => $ejeX[$i],'ejeY' => $cantidad));
            $cantidadP=0;
        }
        
        for ($i=0; $i < count($ejeX); $i++) { 
            $ejeX[$i]=$this->mes(explode("-", $ejeX[$i]));
        }
        for ($i=0; $i < count($ejeXt); $i++) { 
            $ejeXt[$i]=$this->mes(explode("-", $ejeXt[$i]));
        }

        return response()->json(['status'=>'ok',
            'usuarios'=>count($usuarios),
            'departamentos'=>$departamentos,
            'countDepartamentos'=>count($departamentos),
            'proveedores'=>count($proveedores),
            'productos'=>count($productos),
            'stocks'=>$countStocks,
            'stockdepartamentos'=>count($stockdepartamentos),
            'centro_costos'=>count($centro_costos),
            'contratos'=>count($contratos),
            'countpedidos'=>count($pedidos),
            'ultimosPedidos'=>$ultimosPedidos,
            'estado0'=>$estado0,
            'estado1'=>$estado1,
            'estado2'=>$estado2,
            'estado4'=>$estado4,
            'transferencias'=>count($transferencias),
            'ejeX'=>$ejeX,
            'ejeY'=>$ejeY,
            'ejeXt'=>$ejeXt,
            'ejeYt'=>$ejeYt,
                ], 200);

    }

    public function mes($arreglo)
    {
        if ($arreglo[0]=='01') {
           return $arreglo[1].'-Enero'; 
        }else if ($arreglo[0]=='02') {
            return $arreglo[1].'-Febrero';
        }else if ($arreglo[0]=='03') {
            return $arreglo[1].'-Marzo';
        }else if ($arreglo[0]=='04') {
            return $arreglo[1].'-Abril';
        }else if ($arreglo[0]=='05') {
            return $arreglo[1].'-Mayo';
        }else if ($arreglo[0]=='06') {
            return $arreglo[1].'-Junio';
        }else if ($arreglo[0]=='07') {
            return $arreglo[1].'-Julio';
        }else if ($arreglo[0]=='08') {
            return $arreglo[1].'-Agosto';
        }else if ($arreglo[0]=='09') {
            return $arreglo[1].'-Septiembre';
        }else if ($arreglo[0]=='10') {
            return $arreglo[1].'-Octubre';
        }else if ($arreglo[0]=='11') {
            return $arreglo[1].'-Noviembre';
        }else if ($arreglo[0]=='12') {
            return $arreglo[1].'-Diciembre';
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
