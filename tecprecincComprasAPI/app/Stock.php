<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock';

    //public $timestamps = false;

    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
    // Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
    //protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'codigo', 'precio',
			'stock', 'peps', 'valor_reposicion',
			'stock_min', 'partida_parcial', 'categoria_id',
			'proveedor_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = [];

    // Relación de stock con categoria:
	public function categoria()
	{
		// 1 producto del stock pertenece a una categoria
		return $this->belongsTo('App\Categoria', 'categoria_id');
	}

	// Relación de stock con proveedor:
	public function proveedor()
	{
		// 1 producto del stock pertenece a un proveedor
		return $this->belongsTo('App\Proveedor', 'proveedor_id');
	}

    // Relación de stock con pedido (Productos solicitados):
    public function solicitud(){
        // 1 producto del stock puede estar solicitado en varios pedidos
        return $this->belongsToMany('\App\Pedido','pedido_stock','stock_id','pedido_id')
            ->withPivot('cantidad','aprobado','entregado',
                'f_entrega','tipo_entrega','devuelto',
                'cancelado','pendiente','observaciones')/*->withTimestamps()*/; 
    }


}
