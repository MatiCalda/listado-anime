<?php

class TemporadaAnime {
    public $id;
    public $nombre;
    public $cantidadCapitulos;
    public $duracionCapitulo;

    public function __construct($nombre, $cantidadCapitulos, $duracionCapitulo, $id = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->cantidadCapitulos = $cantidadCapitulos;
        $this->duracionCapitulo = $duracionCapitulo;
    }
}