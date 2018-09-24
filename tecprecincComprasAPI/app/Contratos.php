<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contratos extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contratos';

    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
    // Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
    //protected $primaryKey = 'id';

    //public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'cliente', 'vigencia','created_at', 'centro_costos_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['updated_at'];

    //contrato_departamento_centrocosto contrato_id departamento_id controcosto_id

    public function departamentos(){
        return $this->belongsToMany('\App\Departamento','contrato_departamento_centrocosto')
            ->withPivot('contro_costos_id');
    }

    public function centrocostos(){
        return $this->belongsToMany('\App\CentroCostos','contrato_departamento_centrocosto')
            ->withPivot('departamento_id');
    }

}
