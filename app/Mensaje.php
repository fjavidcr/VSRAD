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
}
