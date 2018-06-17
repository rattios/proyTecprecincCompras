<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class trazas extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'trazas';

    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
    // Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
    //protected $primaryKey = 'id';

    //public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //id    stock_id    cantidad    d_emisor_id d_receptor_id   u_emisor_id u_receptor_id   created_at  updated_at

    protected $fillable = ['stock_id', 'cantidad', 'd_emisor_id', 'd_receptor_id', 'u_emisor_id', 'u_receptor_id', 'operacion_id', 'tipo', 'created_at', 'updated_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['updated_at'];

    
    public function stock()
    {
        return $this->belongsTo('App\Stock', 'stock_id','id');
    }
    public function departamento_emisor()
    {
        return $this->belongsTo('App\Departamento', 'd_emisor_id','id');
    }
    public function departamento_receptor()
    {
        return $this->belongsTo('App\Departamento', 'd_receptor_id','id');
    }
    public function usuario_emisor()
    {
        return $this->belongsTo('App\User', 'u_emisor_id','id');
    }
    public function usuario_receptor()
    {
        return $this->belongsTo('App\User', 'u_receptor_id','id');
    }

}
