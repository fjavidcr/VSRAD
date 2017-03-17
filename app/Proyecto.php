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
        return $this->belongsTo('App\User', 'id_cliente');
    }

    public function tecnico() {
        return $this->belongsTo('App\User', 'id_tecnico');
    }


    public function comercial() {
        return $this->belongsTo('App\User', 'id_comercial');
    }

    public function mensajes()
    {
        return $this->hasMany('App\Mensaje', 'id_proyecto');
    }
}
