<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ControlRecepcion extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'controlesRecepcion';

    //public $timestamps = false;

    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
    // Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
    //protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['compra_id', 'f_recepcion','documento',
    	'nota_credito', 'estado'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = [];

    // Relación de ControlRecepcion con compra:
	public function compra()
	{
		// 1 control de recepcion se hace sobre una compra
		return $this->belongsTo('App\Compra', 'compra_id');
	}

}
