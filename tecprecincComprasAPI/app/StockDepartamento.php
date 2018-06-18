<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockDepartamento extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stockdepartamentos';

    //public $timestamps = false;

    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
    // Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
    //protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['stock_id', 'stock',
		'stock_min', 'departamento_id','usuario_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = [];

    // Relación de stockDepartamentos con departamento:
	public function departamento()
	{
		// 1 stockDepartamento (producto) pertenece a un departamento
		return $this->belongsTo('App\Departamento', 'departamento_id');
	}

	// Relación de stockDepartamentos con producto:
	public function producto()
	{
		// 1 stockDepartamento (producto) pertenece a un producto del stock
		return $this->belongsTo('App\Stock', 'stock_id');
	}

    // Relación de stockDepartamentos con User:
    public function usuario()
    {
        // 1 stockDepartamento (producto) pertenece a un usuario
        return $this->belongsTo('App\User', 'usuario_id');
    }
    
}
