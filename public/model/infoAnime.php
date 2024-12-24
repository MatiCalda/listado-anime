<?php
include_once 'user.php';


class InfoAnime
{
    public $nombre;
    public $tipo;
    public $temporadas;
    public $cantidadCapitulos;
    public $duracionCapitulo;
    public $totalMinutos;
    public $totalHoras;

    public function __construct($nombre = null, $tipo = null, $temporadas = null, $cantidadCapitulos = null, $duracionCapitulo = null, $totalMinutos = null, $totalHoras = null)
    {
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->temporadas = $temporadas;
        $this->cantidadCapitulos = $cantidadCapitulos;
        $this->duracionCapitulo = $duracionCapitulo;
        $this->totalMinutos = $totalMinutos;
        $this->totalHoras = $totalHoras;
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
                        <option selected value="Serie">Serie</option>
                        <option value="Pelicula">Pelicula</option>
                        <option value="OVA">OVA</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                    <input type="text" aria-label="Last name" placeholder="temporadas" class="form-control" name="temporada">
                </div>
                <div class="col-4 col-sm-6 col-md-4 col-lg-1">
                    <input type="number" aria-label="Last name" placeholder="capitulos" class="form-control" name="cant_capitulos">
                </div>
                <div class="col-4 col-sm-6 col-md-4 col-lg-1">
                    <input type="number" aria-label="Last name" placeholder="duracion" class="form-control" name="duracion_capitulo">
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
        <tr>
          <td class=\"align-middle text-nowrap\">" . $this->nombre . "</td>
          <td class=\"align-middle\">" . $this->tipo . "</td>
          <td class=\"align-middle text-nowrap\">" . nl2br($this->temporadas) . "</td>
          <td class=\"align-middle\">" . $this->cantidadCapitulos . "</td>
          <td class=\"align-middle\">" . $this->duracionCapitulo . "</td>
          <td class=\"align-middle\">" . $this->totalMinutos . "</td>
          <td class=\"align-middle\">" . $this->totalHoras . "</td>
        </tr>"
        ;
        echo $html;
    }

}