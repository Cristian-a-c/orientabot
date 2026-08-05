<?php

namespace App\Models\Usuario;

use Illuminate\Database\Eloquent\Model;

class UsuarioModel extends Model
{
    protected $table = 'tusuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'name',
        'email',
        'contrasena'
    ];

    public function roles()
    {
        return $this->hasMany(\App\Models\Rol\RolModel::class, 'id_usuario');
    }
}
