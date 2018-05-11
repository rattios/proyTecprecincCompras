<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pedidos';

    //public $timestamps = false;

    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
    // Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
    //protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['estado', 'usuario_id','centro_costos_id','contrato_id','aprobar'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = [];

    // Relación de pedidos con usuarios:
	public function usuario()
	{
		// 1 pedido pertenece a un usuario
		return $this->belongsTo('App\User', 'usuario_id');
	}

    // Relación de pedidos con stock (Productos solicitados):
    public function solicitud(){
        // 1 pedido contiene muchos productos solicitados del stock
        return $this->belongsToMany('\App\Stock','pedido_stock','pedido_id','stock_id')
            ->withPivot('cantidad','aprobado','entregado',
                'f_entrega','tipo_entrega','devuelto',
                'cancelado','pendiente','observaciones','centro_costos_id')/*->withTimestamps()*/; 
    }

    // Relación de pedido con presupuestos:
    public function presupuestos()
    {
        // 1 pedido puede tener varios presupuestos
        return $this->hasMany('App\Presupuesto', 'pedido_id');
    }
    // Relación de pedido con centro de costos:
    public function centro_costos()
    {
        // 1 pedido puede tener varios presupuestos
        return $this->belongsTo('App\CentroCostos', 'centro_costos_id');
    }
    public function contratos()
    {
        // 1 pedido puede tener varios presupuestos
        return $this->belongsTo('App\Contratos', 'contrato_id');
    }

}
