<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tipos';

    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
    // Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
    //protected $primaryKey = 'id';

    //public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'codigo'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    // Relación de tipo con categorias:
    public function categorias()
    {
        // 1 tipo puede tener varias categorias
        return $this->hasMany('App\Categoria', 'tipo_id');
    }

    
}
