<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'departamentos';

    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
    // Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
    //protected $primaryKey = 'id';

    //public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'codigo','telefono'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at'];

    // Relación de departamento con usuarios:
    public function usuarios()
    {
        // 1 departamento tiene muchos usuarios
        return $this->hasMany('App\User', 'departamento_id');
    }

    // Relación de departamento con stockDepartamentos:
    public function productos()
    {
        // 1 departamento tiene muchos productos en su stock
        return $this->hasMany('App\StockDepartamento', 'departamento_id');
    }

    // Relación de departamento con stock:
    public function permisos_productos(){
        // 1 un departamento puede tener permiso para ver muchos productos del stock
        return $this->belongsToMany('\App\Stock','stock_permisos_departs','departamento_id','stock_id')/*->withTimestamps()*/; 
    }

}
