<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
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
        'codigo_verificacion','departamento_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'created_at', 'updated_at'];

    // Relación de usuario con pedidos:
    public function pedidos()
    {
        // Una usuario tiene muchos pedidos
        return $this->hasMany('App\Pedido', 'usuario_id');
    }

    // Relación de pedidos con departamento:
    public function departamento()
    {
        // 1 pedido pertenece a un departamento
        return $this->belongsTo('App\Departamento', 'departamento_id');
    }
}
