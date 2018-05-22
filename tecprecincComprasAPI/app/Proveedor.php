<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'proveedores';

    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
    // Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
    //protected $primaryKey = 'id';

    //public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['razon_social', 'nombre_fantacia', 'cuit',
		'telefono', 'fax', 'email', 'habilitado', 'estado', 'calificacion', 'direccion', 'calle','nro','ciudad','provincia','pais','forma_pago'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = [];

    // Relación de proveedor con productos:
    public function productos()
    {
        // 1 proveedor puede tener varios productos
        return $this->belongsToMany('\App\Producto','proveedores_productos','proveedor_id','producto_id')
            ->withPivot('precio')/*->withTimestamps()*/; 
    }

    // Relación de proveedor con stockDepartamentos:
    public function stockDepartamentos()
    {
        // 1 proveedor puede tener varios productos en los departamentos
        return $this->hasMany('App\StockDepartamento', 'proveedor_id');
    }

    // Relación de proveedor con stock:
    public function stock()
    {
        // 1 proveedor puede tener varios productos en el stock(almacen)
        return $this->hasMany('App\Stock', 'proveedor_id');
    }

    // Relación de proveedor con categorias:
    public function categorias()
    {
        // 1 proveedor puede tener varias categorias
        return $this->belongsToMany('\App\Categoria','proveedores_categorias','proveedor_id','categoria_id')
            /*->withTimestamps()*/; 
    }
}
