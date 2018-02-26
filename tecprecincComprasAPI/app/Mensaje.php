<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mensajes';

    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
    // Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
    //protected $primaryKey = 'id';

    //public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['estado', 'tipo', 'asunto', 'msg', 'adjunto', 'departamento_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    // Relación de mensaje con departamento:
    public function departamento()
    {
        // 1 mensaje pertenece a un (se genera para un) departamento
        return $this->belongsTo('App\Departamento', 'departamento_id');
    }

}
