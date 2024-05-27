<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificados extends Model
{
    use HasFactory;

    public function usuario()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function cliente()
    {
        return $this->hasOne(Clientes::class, 'id', 'cliente_id');
    }

    public function curso()
    {
        return $this->hasOne(Cursos::class, 'id', 'curso_id');
    }

    public function hora()
    {
        return $this->hasOne(Horarios::class, 'id', 'horario_id');
    }

    public function representanteLegal()
    {
        return $this->hasOne(RepresentanteLegal::class, 'id', 'representante_legal_id');
    }

    public function instructor()
    {
        return $this->hasOne(Instructores::class, 'id', 'instructor_id');
    }

    public function aliado()
    {
        return $this->hasOne(Aliados::class, 'id', 'aliado_id');
    }

    public function asesor()
    {
        return $this->hasOne(Asesores::class, 'id', 'asesor_id');
    }
}
