<?php
include_once 'user.php';
include_once 'temporadaAnime.php';


class InfoAnime
{
    public $id;
    public $nombre;
    public $tipo;
    public $temporadas;
    public $cantidadCapitulos;
    public $duracionCapitulo;
    public $totalMinutos;
    public $totalHoras;

    public function __construct($id = null ,$nombre = null, $tipo = null, $temporadas = null, $cantidadCapitulos = null, $duracionCapitulo = null, $totalMinutos = null, $totalHoras = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->temporadas = [];
        $this->cantidadCapitulos = $cantidadCapitulos;
        $this->duracionCapitulo = $duracionCapitulo;
        $this->totalMinutos = $totalMinutos;
        $this->totalHoras = $totalHoras;
    }
    public function addTemporada($temporada)
    {
        $this->temporadas[] = $temporada;

        $this->cantidadCapitulos += $temporada->cantidadCapitulos;
        $this->duracionCapitulo = $temporada->duracionCapitulo;

        $this->totalMinutos = $this->cantidadCapitulos * $this->duracionCapitulo;
    }
    public function getNombresTemporadas()
    {
        $nombres = "";
        if(sizeof($this->temporadas) > 1){
            foreach ($this->temporadas as $temporada) {
                $nombres .= $temporada->nombre . "\n";
            }
        }
        return $nombres;
    }
    public function getTotalHoras()
    {
        return sprintf("%02d",intdiv($this->totalMinutos, 60)) . ':' . sprintf("%02d", ($this->totalMinutos % 60));
    }
    public function renderInput()
    {
        $html = '
        <form action="controller/homeController.php" method="POST" class="container-fluid py-4">
            <div class="row gx-0">
                <div class="col-9 col-sm-6 col-md-4 col-lg-4">
                    <input type="text" aria-label="First name" placeholder="nombre" class="form-control" name="nombre">
                </div>
                <div class="col-3 col-sm-6 col-md-4 col-lg-1">
                    <select class="form-select" aria-label="Default select example" class="w-100" name="tipo">
                        <option disabled >tipo...</option>
                        <option selected value="S">Serie</option>
                        <option value="P">Pelicula</option>
                        <option value="O">OVA</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                    <input type="text" aria-label="Last name" placeholder="temporadas" class="form-control" name="temporada">
                </div>
                <div class="col-4 col-sm-6 col-md-4 col-lg-1">
                    <input type="number" aria-label="Last name" placeholder="capitulos" class="form-control" name="cant_capitulos">
                </div>
                <div class="col-4 col-sm-6 col-md-4 col-lg-1">
                    <input type="number" aria-label="Last name" placeholder="duracion" class="form-control" name="duracion_capitulo" value=20>
                </div>
                <div class="col-4 col-sm-6 col-md-4 col-lg-1">
                    <button type="submit" class="btn btn-success w-100" name="guardar">Guardar</button>
                </div>
            </div>
        </form>
        ';

        echo $html;
    }
    public function renderItem()
    {
        $html = "
        <tr role=\"button\" class=\"trigger-modal\"  data-bs-id=". $this->id ." data-bs-nombreAnime=\"". $this->nombre ."\">
          <td class=\"align-middle text-nowrap\">" . $this->nombre . "</td>
          <td class=\"align-middle\">" . $this->tipo . "</td>
          <td class=\"align-middle text-nowrap\">" . //nl2br($this->temporadas) 
            nl2br($this->getNombresTemporadas())
            . "</td>
          <td class=\"align-middle\">" . $this->cantidadCapitulos . "</td>
          <td class=\"align-middle\">" . $this->duracionCapitulo . "</td>
          <td class=\"align-middle\">" . $this->totalMinutos . "</td>
          <td class=\"align-middle\">" . $this->getTotalHoras() . "</td>
        </tr>"
        ;
        echo $html;
    }



}