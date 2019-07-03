<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Intento extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'intentos';

    
     protected $fillable = ['id', 'usuario','clave','created_at',
        'updated_at'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = [];

}
