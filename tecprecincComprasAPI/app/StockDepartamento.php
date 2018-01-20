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
    protected $table = 'stockDepartamentos';

    //public $timestamps = false;

    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
    // Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
    //protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'codigo',
		'stock', 'stock_min',
		'categoria_id', 'proveedor_id',
		'departamento_id'];

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

	// Relación de stockDepartamentos con categoria:
	public function categoria()
	{
		// 1 stockDepartamento (producto) pertenece a una categoria
		return $this->belongsTo('App\Categoria', 'categoria_id');
	}

	// Relación de stockDepartamentos con proveedor:
	public function proveedor()
	{
		// 1 stockDepartamento (producto) pertenece a un proveedor
		return $this->belongsTo('App\Proveedor', 'proveedor_id');
	}

    
}
