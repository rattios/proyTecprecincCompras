<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
    
});

Route::group(  ['middleware' =>'cors'], function(){

    //----Pruebas LoginController
    Route::post('/login/web','LoginController@loginWeb');
    //Route::post('/login/app','LoginController@loginApp');
    //Route::post('/validar/token','LoginController@validarToken'); 

    //----Pruebas PasswordController
    //Route::get('/password/cliente/{correo}','PasswordController@generarCodigo');
    //Route::get('/password/codigo/{codigo}','PasswordController@validarCodigo');

        //----Pruebas DashboardController
        //Route::get('/dashboard','DashboardController@index');
        Route::get('/dashboard','DashboardController@dashboard');

        //----Pruebas DepartamentoController
        Route::get('/departamentos','DepartamentoController@index');
        Route::post('/departamentos','DepartamentoController@store'); 
        Route::put('/departamentos/{id}','DepartamentoController@update');
        Route::delete('/departamentos/{id}','DepartamentoController@destroy');
        Route::get('/departamentos/{id}','DepartamentoController@show');

        //----Pruebas UsuarioController
        Route::get('/usuarios','UsuariosController@usuarios');
       // Route::get('/usuarios','UsuarioController@index');
        Route::get('/usuarios/pedidos','UsuarioController@usuariosPedidos');
        Route::post('/usuarios','UsuarioController@store');
        Route::put('/usuarios/{id}','UsuarioController@update');
        Route::delete('/usuarios/{id}','UsuarioController@destroy');
        Route::get('/usuarios/{id}','UsuarioController@show');
        Route::get('/usuarios/{id}/pedidos','UsuarioController@usuarioPedidos');

        //----Pruebas TipoController
        Route::get('/tipos','TipoController@index');
        Route::post('/tipos','TipoController@store');
        Route::put('/tipos/{id}','TipoController@update');
        Route::delete('/tipos/{id}','TipoController@destroy');
        Route::get('/tipos/{id}','TipoController@show');

        //----Pruebas RubroController
        Route::get('/rubros','RubroController@index');
        Route::post('/rubros','RubroController@store');
        Route::put('/rubros/{id}','RubroController@update');
        Route::delete('/rubros/{id}','RubroController@destroy');
        Route::get('/rubros/{id}','RubroController@show');

        //----Pruebas CategoriaController
        Route::get('/categorias','CategoriaController@index');
        Route::get('/categorias','CategoriaController@index');
        Route::get('/categorias','CategoriaController@index');
        Route::get('/categorias/productos','CategoriaController@categoriasProductos');
        Route::post('/categorias','CategoriaController@store');
        Route::put('/categorias','CategoriaController@update');
        Route::delete('/categorias/{id}','CategoriaController@destroy');
        Route::get('/categorias/{id}','CategoriaController@show');
        Route::get('/categorias/{id}/productos','CategoriaController@categoriaProductos');

        Route::get('/fullcategorias','CategoriaController@indexfull');
        Route::get('/categs','CategController@index');

        //----Pruebas ProductoController
        Route::get('/productos','ProductoController@index');
        Route::get('/productos/categoria','ProductoController@productosCategoria');
        Route::post('/productos/{categoria_id}','ProductoController@store');
        Route::put('/productos/{id}','ProductoController@update');
        Route::delete('/productos/{id}','ProductoController@destroy');
        Route::get('/productos/{id}','ProductoController@show');
        Route::get('/productos/{id}/categoria','ProductoController@productoCategoria');
        Route::get('/productos/{id}/proveedores','ProductoController@productoProveedores');
        
        //----Pruebas PedidoController
        Route::get('/pedidos','PedidoController@index');
        Route::get('/aprobar','PedidoController@aprobar');
        Route::get('/pedidos0','PedidoController@index0');
        Route::get('/pedidos1','PedidoController@index1');
        Route::get('/pedidos2','PedidoController@index2');
        Route::get('/pedidos4','PedidoController@index4');
        Route::get('/pedidos/departamento/{id}','PedidoController@index_departamentos');
        Route::post('/pedidos','PedidoController@store');
        Route::put('/pedidos/{id}','PedidoController@update');
        Route::delete('/pedidos/{id}','PedidoController@destroy');
        Route::get('/pedidos/{id}','PedidoController@show');
        Route::post('/pedidos/picking','PedidoController@picking');
        Route::get('/pedidos/ubicar/{stock_id}','PedidoController@ubicarProducto');

        //----Pruebas ProveedorController
        Route::get('/proveedores','ProveedorController@index');
        Route::post('/proveedores','ProveedorController@store');
        Route::put('/proveedores/{id}','ProveedorController@update');
        Route::delete('/proveedores/{id}','ProveedorController@destroy');
        Route::get('/proveedores/{id}','ProveedorController@show');
        Route::get('/proveedores/{id}/categorias','ProveedorController@proveedorCats');

        //----Pruebas StockController
        Route::get('/stock','StockController@index');
        Route::get('/stock/permitido','StockController@stockPermitido');
        Route::get('/stock/transferencias','StockController@stockTransferencias');
        Route::get('/todos','StockController@todos');
        Route::get('/stockUso','StockController@indexCategoriasUso');
        Route::get('/stockConsumo','StockController@indexCategoriasConsumo');
        Route::get('/stockServicio','StockController@indexCategoriasServicio');
        Route::post('/stock','StockController@store');
        Route::put('/stock/{id}','StockController@update');
        Route::delete('/stock/{id}','StockController@destroy');
        Route::get('/stock/{id}','StockController@show');
        Route::get('/stock_centro_costos','StockController@index_centro_costos');

        Route::get('/prestock','PrestockController@index');
        //----Pruebas StockController
        Route::get('/stockDepar','StockDepartamentoController@StockDepartamento');
        Route::get('/stockDepartamento','StockDepartamentoController@index');
        Route::post('/stockDepartamento','StockDepartamentoController@store');
        Route::put('/stockDepartamento/{id}','StockDepartamentoController@update');
        Route::delete('/stockDepartamento/{id}','StockDepartamentoController@destroy');
        Route::get('/stockDepartamento/{id}','StockDepartamentoController@show');

        //----Pruebas TransferenciaController
        Route::get('/transferencias','TransferenciaController@index');
        Route::post('/transferencias','TransferenciaController@store');
        Route::put('/transferencias/{id}','TransferenciaController@update');
        Route::delete('/transferencias/{id}','TransferenciaController@destroy');
        Route::get('/transferencias/index/pura','TransferenciaController@indexTranfPuras');
        Route::get('/transferencias/index/devolucion','TransferenciaController@indexDevoluciones');
        Route::get('/transferencias/index/patrimonial','TransferenciaController@indexTranfPatri');
        Route::get('/transferencias/{id}','TransferenciaController@show');
        Route::get('/transferencias/departamento/{departamento_id}','TransferenciaController@transfsDep');
        Route::post('/transferencias/aprobar/{id}','TransferenciaController@aprobarTrasf');
        Route::post('/transferencias/pura','TransferenciaController@transferencia');
        Route::post('/transferencias/devolucion','TransferenciaController@devolucion');
        Route::post('/transferencias/patrimonial','TransferenciaController@transferenciaPatrimonial');


        //----Pruebas MensajeController
        Route::get('/mensajes','MensajeController@index');
        Route::post('/mensajes','MensajeController@store');
        Route::put('/mensajes/{id}','MensajeController@update');
        Route::delete('/mensajes/{id}','MensajeController@destroy');
        Route::get('/mensajes/{id}','MensajeController@show');
        Route::get('/mensajes/departamento/{departamento_id}','MensajeController@mensajesDep');

        Route::get('/centro_costos','CentroCostosController@index');
        Route::post('/centro_costos','CentroCostosController@store');
        Route::put('/centro_costos/{id}','CentroCostosController@update');
        Route::delete('/centro_costos/{id}','CentroCostosController@destroy');
        
        Route::get('/contratos','ContratosController@index');
        Route::post('/contratos','ContratosController@store');
        Route::put('/contratos/{id}','ContratosController@update');
        Route::delete('/contratos/{id}','ContratosController@destroy');

        Route::get('/almacen','AlmacenController@index');
        Route::post('/almacen','AlmacenController@store'); 
        Route::put('/almacen/{id}','AlmacenController@update');
        Route::delete('/almacen/{id}','AlmacenController@destroy');
        Route::get('/almacen/{id}','AlmacenController@show');

        Route::get('/trazas','TrazasController@index');
        Route::post('/trazas','TrazasController@store'); 
        Route::put('/trazas/{id}','TrazasController@update');
        Route::delete('/trazas/{id}','TrazasController@destroy');
        Route::get('/trazas/{id}','TrazasController@show');


    Route::group(['middleware' => 'jwt-auth'], function(){



    });
});
