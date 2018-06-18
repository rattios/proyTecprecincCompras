<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transferencia extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transferencias';

    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
    // Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
    //protected $primaryKey = 'id';

    //public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['estado', 'cantidad_transf', 'stock_id',
        'departamento_id', 'tipo', 'almacen', 'receptor_id','usuario_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    // Relación de transferencia con departamento:
    public function departamento()
    {
        // 1 transferencia pertenece a un departamento
        return $this->belongsTo('App\Departamento', 'departamento_id');
    }

    /*// Relación de transferencia con stockDepartamento:
    public function stockDep()
    {
        // 1 transferencia pertenece se genera para un producto del stock del departamento
        return $this->belongsTo('App\StockDepartamento', 'stock_id');
    }*/

    // Relación de transferencia con stock:
    public function stockCentral()
    {
        // 1 transferencia pertenece se relaciona con un producto de stock central
        return $this->belongsTo('App\Stock', 'stock_id');
    }

    // Relación de transferencia patrimonial con departamento:
    public function receptor()
    {
        // 1 transferencia  patrimonial pertenece a un (se genera para un) departamento receptor
        return $this->belongsTo('App\Departamento', 'receptor_id');
    }

    // Relación de transferencia con usuarios:
    public function usuario()
    {
        // 1 transferencia pertenece a un usuario
        return $this->belongsTo('App\User', 'usuario_id');
    }
}
