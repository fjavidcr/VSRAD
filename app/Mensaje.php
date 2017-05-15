<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    //
    protected $table = "mensajes";

    public function proyecto() {
        return $this->belongsTo('App\Proyecto', 'id_proyecto');
    }

    public function nombre_cliente(){
        $cliente = \App\User::findOrFail($this->id_cliente);
        return $cliente->getCompleteName();
    }

    public function nombre_tecnico(){
        $tecnico = \App\User::findOrFail($this->id_tecnico);
        return $tecnico->getCompleteName();
    }

    public function nombre_comercial(){
        $comercial = \App\User::findOrFail($this->id_comercial);
        return $comercial->getCompleteName();
    }
}
