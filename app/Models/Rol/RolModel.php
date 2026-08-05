<?php

namespace App\Models\Rol;

use Illuminate\Database\Eloquent\Model;

class RolModel extends Model
{
    protected $table = 'troles';
    protected $primaryKey = 'id_rol';

    protected $fillable = [
        'nombre_rol',
        'id_usuario',
        'admin_id'
    ];

    //  Relación hacia el usuario
    public function usuario()
    {
        return $this->belongsTo(\App\Models\Usuario\UsuarioModel::class, 'id_usuario');
    }

    //  Relación hacia el admin
    public function admin()
    {
        return $this->belongsTo(\App\Models\Admin\Admin::class, 'admin_id');
    }
}