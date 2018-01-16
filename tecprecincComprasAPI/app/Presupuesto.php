<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'presupuestos';

    //public $timestamps = false;

    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
    // Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
    //protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['pedido_id', 'proveedor_id','estado',
    	'f_envio', 'f_respuesta','f_entrega', 'documento'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = [];

    // Relación de presupuesto con pedido:
	public function pedido()
	{
		// 1 presupuesto pertenece a un pedido
		return $this->belongsTo('App\Pedido', 'pedido_id');
	}

	// Relación de presupuesto con proveedor:
	public function proveedor()
	{
		// 1 presupuesto pertenece a un proveedor
		return $this->belongsTo('App\Proveedor', 'proveedor_id');
	}

    // Relación de presupuesto con compra:
    public function compra()
    {
        // 1 presupuesto puede generar una compra
        return $this->hasOne('App\Compra', 'presupuesto_id');
    }
}
