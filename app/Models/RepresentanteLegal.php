<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepresentanteLegal extends Model
{
    use HasFactory;

    public function documento()
    {
        return $this->hasOne(TiposDocumentos::class, 'id', 'tipo_documento_id');
    }
}
