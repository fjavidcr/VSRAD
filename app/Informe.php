<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Informe extends Model
{
    //
    protected $table = "informes";

    public function informe() {
        return $this->belongsTo('App\User', 'id_director');
    }
}
