<?php

class TemporadaAnime {
    public $nombre;
    public $cantidadCapitulos;
    public $duracionCapitulo;

    public function __construct($nombre, $cantidadCapitulos, $duracionCapitulo) {
        $this->nombre = $nombre;
        $this->cantidadCapitulos = $cantidadCapitulos;
        $this->duracionCapitulo = $duracionCapitulo;
    }
}