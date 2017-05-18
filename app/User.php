<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;


class User extends Authenticatable
{
    //use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'rol'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static $roles = [
        'cliente', 'comercial', 'tecnico', 'director_comercial', 'administrador'
    ];

    public static $title = [
        'Cliente', 'Comercial', 'Técnico', 'Director Comercial', 'Administrador'
    ];


    public function hasRol($rol)
    {
        return User::$roles[$this->rol] == $rol;
    }

    public function getRol() {
        return User::$roles[$this->rol];
    }

    public function getTitle() {
        return User::$title[$this->rol];
    }

    public function hasId_comercial($id_comercial)
    {
        return $this->id_comercial == $id_comercial;
    }

    public function getId_comercial() {
        return $this->id_comercial;
    }

    public function getCompleteName() {
        return User::$title[$this->rol] . ": " . $this->name .' '. $this->apellidos;
    }

    public function getName() {
        return  $this->name .' '. $this->apellidos;
    }

    public function isRegistered(){
        if($this->apellidos == "" && $this->direccion_fisica == "" && $this->telefono =="" && $this->dni =="")
            return false;
        return true;
    }

    public function informes()
    {
        return $this->hasMany('App\Informe', 'id_director');
    }

    public function proyectos()
    {
        return $this->hasMany('App\Proyecto', 'id_cliente');
    }

    public function proyectos_tecnico()
    {
        return $this->hasMany('App\Proyecto', 'id_tecnico');
    }

    public static function numero_clientes_comercial($id){

        $clientes = DB::table('users')->where('id_comercial', '=', $id)->get();
        return count($clientes);
    }

    public static function numero_clientes_resgistrados($id){
        $clientes = DB::table('users')->where('id_comercial', '=', $id)->get();
        $num = 0;
        foreach ($clientes as $c)
            if(isset($c->dni))
                $num++;
        return $num;
    }

    public static function numero_clientes_invitados($id){
        $clientes = DB::table('users')->where('id_comercial', '=', $id)->get();
        $num = 0;
        foreach ($clientes as $c)
            if(!isset($c->dni))
                $num++;
        return $num;
    }

    public static function numero_proyectos_no_validados($id){
        $clientes = DB::table('users')->where('id_comercial', '=', $id)->get();
        $proyectos = DB::table('proyectos')->where('estado', '=', 3)->get();
        $num = 0;
        foreach ($clientes as $c)
            foreach ($proyectos as $p)
                if($c->id == $p->id_cliente)
                    $num++;
        return $num;
    }

    public static function numero_proyectos_validados($id){
        $clientes = DB::table('users')->where('id_comercial', '=', $id)->get();
        $proyectos = DB::table('proyectos')->where('estado', '=', 2)->get();
        $num = 0;
        foreach ($clientes as $c)
            foreach ($proyectos as $p)
                if($c->id == $p->id_cliente)
                    $num++;
        return $num;
    }

    public static function media_proyectos_rechazados($id){
        $clientes = DB::table('users')->where('id_comercial', '=', $id)->get();
        $proyectos = DB::table('proyectos')->where('estado', '=', 5)->get();
        $cont = 0;
        $media = 0;
        foreach ($clientes as $c)
            foreach ($proyectos as $p)
                if($c->id == $p->id_cliente){
                    $cont++;
                    $media += $p->coste;
                }
        if ($cont > 0)
            $media = $media / $cont;
        else
            $media = 0;

        return $media;
    }

    public static function media_proyectos_comprados($id){
        $clientes = DB::table('users')->where('id_comercial', '=', $id)->get();
        $proyectos = DB::table('proyectos')->where('estado', '=', 4)->get();
        $cont = 0;
        $media = 0;
        foreach ($clientes as $c)
            foreach ($proyectos as $p)
                if($c->id == $p->id_cliente){
                    $cont++;
                    $media += $p->coste;
                }

        if ($cont > 0)
            $media = $media / $cont;
        else
            $media = 0;

        return $media;
    }

}
