<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    use HasFactory;

    public function aliado()
    {
        return $this->hasOne(Aliados::class, 'id', 'aliado_id');
    }

    public function asesor()
    {
        return $this->hasOne(Asesores::class, 'id', 'asesor_id');
    }

    public function documentos(){
        return $this->hasMany(ClienteDocumentos::class, 'cliente_id', 'id');
    }
}
