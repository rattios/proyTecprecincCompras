<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'usuarios';

    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
    // Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
    //protected $primaryKey = 'id';

    //public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user', 'password', 'email',
         'nombre', 'apellido', 'telefono', 'rol',
        'codigo_verificacion','departamento_id','created_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'created_at', 'updated_at'];

    public function departamento()
    {
        // 1 usuario puede tener varios departamentos
        return $this->belongsToMany('\App\Departamento','usuarios_departamentos','usuarios_id','departamentos_id')/*->withTimestamps()*/; 
    }

}
