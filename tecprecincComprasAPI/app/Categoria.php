<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categorias';

    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
    // Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
    //protected $primaryKey = 'id';

    //public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'codigo', 'tipo_id', 'rubro_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    // Relación de categoria con productos:
    public function productos()
    {
        // 1 categoria puede tener varios productos
        return $this->hasMany('App\Producto', 'categoria_id');
    }

    /*// Relación de categoria con stockDepartamentos:
    public function stockDepartamentos()
    {
        // 1 categoria puede tener varios productos en los departamentos
        return $this->hasMany('App\StockDepartamento', 'categoria_id');
    }*/

    // Relación de categoria con stock:
    public function stock()
    {
        // 1 categoria puede tener varios productos en el stock(almacen)
        return $this->hasMany('App\Stock', 'categoria_id');
    }

    // Relación de categoria con tipo:
    public function tipo()
    {
        // 1 categoria pertenece a un tipo
        return $this->belongsTo('App\Tipo', 'tipo_id');
    }

    // Relación de categoria con rubro:
    public function rubro()
    {
        // 1 categoria pertenece a un rubro
        return $this->belongsTo('App\Rubro', 'rubro_id');
    }
}
