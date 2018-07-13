<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'compra';

    //public $timestamps = false;

    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
    // Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
    //protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /*protected $fillable = ['presupuesto_id', 'estado','f_envio',
    	'confir_ajuste', 'f_respuesta','confir_rec_oc'];*/
     protected $fillable = ['proveedor_id', 'productos','observaciones',
        'estado','nota'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = [];

    // Relación de presupuesto con proveedor:
    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor', 'proveedor_id');
    }

    // Relación de compra con presupuesto:
	public function presupuesto()
	{
		// 1 compra pertenece a un presupuesto
		return $this->belongsTo('App\Presupuesto', 'presupuesto_id');
	}

    // Relación de compra con controlRecepcion:
    public function controlRecepcion()
    {
        // 1 compra genera un control de recepcion
        return $this->hasOne('App\ControlRecepcion', 'compra_id');
    }
}
