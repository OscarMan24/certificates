<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aliados extends Model
{
    use HasFactory;

    public function sector()
    {
        return $this->hasOne(Sectores::class, 'id', 'economic_sector');
    }
}
