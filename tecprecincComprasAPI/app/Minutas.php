<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Minutas extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'minutas';

    //public $timestamps = false;

    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
    // Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
    //protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['proveedores', 'productos','observaciones'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = [];


}
