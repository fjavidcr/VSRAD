<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $table = "proyectos";

    public static $estados = [
        'no_pendiente', 'pendiente', 'validado', 'no_validado', 'comprado', 'rechazado', 'solicitud_presupuesto_final', 'presupuesto_final'
    ];

    public static $tituloEstados = [
        'No pendiente', 'Pendiente', 'Validado', 'No validado', 'Comprado', 'Rechazado', 'Solicitud de presupuesto final','Presupuesto final enviado'
    ];

    public function getEstado() {
        return Proyecto::$estados[$this->estado];
    }

    public function getTecnico() {
        return $tecnico = \App\User::findorFail($this->id_tecnico);
    }

    public function getTituloEstado() {
        return Proyecto::$tituloEstados[$this->estado];
    }

    public function isValidado() {
        return $this->getEstado() == "validado";
    }

    public function getCliente()
    {
        $cliente = \App\User::findOrFail($this->id_cliente);
        return $cliente;
    }

    public function puedeEliminar(){
        if($this->getEstado() == "no_pendiente" || $this->getEstado() == "no_validado" || $this->getEstado() == "rechazado")
            return true;
        return false;
    }

    //TODO: revisar las relaciones

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
