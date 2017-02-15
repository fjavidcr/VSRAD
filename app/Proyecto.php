<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $table = "proyectos";

    public function isValidado() {
        if ($this->validado == 1)
            return "Proyecto validado";

        return "Pendiente de validación";
    }

    public function cliente() {
        return $this->belongsTo('App\User', 'cliente_id');
    }

    public function tecnico() {
        return $this->belongsTo('App\User', 'tecnico_id');
    }

    public function comercial() {
        return $this->belongsTo('App\User', 'comercial_id');
    }
}
