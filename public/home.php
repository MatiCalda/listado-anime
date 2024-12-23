<?php

try {
  require_once '../vendor/autoload.php';
  require_once 'config.php';
  include_once 'model/navBar.php';
  include_once 'model/infoAnime.php';
  include_once 'model/user.php';
  session_start();


  if (isset($_SESSION['user'])) {
    $arrInfoAnime = unserialize($_SESSION['listaAnimes']);
    $arrStats = unserialize($_SESSION['statsAnime']);
  } else {
    session_destroy();
    header('location: notLogged.php');
    die();
  }

  if (isset($_GET['error'])) {
    //echo "error: " . $_GET['error'];
    $html = '
        <div class="alert alert-danger mt-5 mx-5" role="alert">
          Error de Conexion
        </div>
        ';
  }

} catch (Exception $e) {
  //echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
  $html = '
    <div class="alert alert-info mt-5 mx-5" role="alert">
      Error de Conexion
    </div>
    ';
}
?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <title>AnimeLst</title>

</head>

<body>

  <?php
  if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
    $nav = new NavBar('home', $user);
    $infoAnime = new InfoAnime();
    $nav->render();
    $infoAnime->renderInput();
    ?> <!-- si el usuario existe, muestro esto -->






    <?php
  }

  ?>


  <div class="container-fluid">
    <div class="row">
      <div class="col-6 col-sm-6 col-md-4 col-lg-2 border"><strong>Total (hs): </strong><?php echo $arrStats['total_horas']; ?> hs</div>
      <div class="col-6 col-sm-6 col-md-4 col-lg-3 border"><strong>Total (dias): </strong><?php echo $arrStats['total_tiempo']; ?></div>
      <div class="col-4 col-sm-6 col-md-4 col-lg-1 border"><strong>Series: </strong><?php echo $arrStats['series']; ?></div>
      <div class="col-4 col-sm-6 col-md-4 col-lg-2 border"><strong>Peliculas: </strong><?php echo $arrStats['peliculas']; ?></div>
      <div class="col-4 col-sm-6 col-md-4 col-lg-1 border"><strong>OVAs: </strong><?php echo $arrStats['ovas']; ?></div>
      <div class="col-6 col-sm-6 col-md-4 col-lg-1 border"><strong>Total: </strong><?php echo $arrStats['cantidad_total']; ?></div>
      <div class="col-6 col-sm-6 col-md-4 col-lg-2 border"><strong>Capitulos: </strong><?php echo $arrStats['capitulos']; ?></div>
    </div>
  </div>


  <div style="overflow-x:auto" class=" mt-4">
    <table class="table table-bordered table-hover">
      <thead>
        <tr class="table-light">
          <th class="col-3">Nombre</th>
          <th class="col-1">Tipo</th>
          <th class="col-3">Temporadas</th>
          <th class="col-1">Capitulos</th>
          <th class="col-1">Duracion</th>
          <th class="col">Total (min)</th>
          <th class="col">Total (hr)</th>
        </tr>
      </thead>
      <tbody>

        <?php


        for ($i = 0; $i < count($arrInfoAnime); $i++) {
          $arrInfoAnime[$i]->renderItem();
        }


        ?>


      </tbody>
    </table>
  </div>

  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
    crossorigin="anonymous"></script>



  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
      -->
</body>

</html>