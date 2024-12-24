<?php
class User{
    public $nombre;
    public $correo;
    public $picture;
    
    public function __construct($nombre="", $correo="", $picture="") {
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->picture = $picture;
    }
}

