<?php
require_once '../../vendor/autoload.php';
require_once '../config.php';
include_once '../model/user.php';
include_once '../model/infoAnime.php';
include_once '../model/temporadaAnime.php';
session_start();

// TODO: Eliminar - solo uso en local
$user = new User();
$user->nombre = "matias";
$_SESSION['user'] = serialize($user);
// TODO: fin Eliminar - solo uso en local

$location = '../notLogged.php';

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    // get profile info
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $correo = $google_account_info->email;
    $nombre = $google_account_info->name;
    $picture = $google_account_info->picture;

    // Estos datos son los que obtenemos....	

    try {
        // Preparamos
        $stmt = $conn->prepare("SELECT correo FROM usuarios WHERE correo = :correo");
        // Especificamos el fetch mode antes de llamar a fetch()
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        // Ejecutamos
        $stmt->execute(['correo' => $correo]);

        if ($row = $stmt->fetch()) { // el usuario existe
            //echo "Bienvenido: " . $nombre ." <br>";
            $user = new User();
            $user->nombre = $nombre;
            $user->picture = $picture;

            //var_dump($user);
            $_SESSION['user'] = serialize($user);

        } else { // el usuario no existe
            // echo "No existe cuenta con: " . $correo;
            // remove all session variables
            session_unset();
            // destroy the session
            session_destroy();
            header('location: ../notLogged.php');
            die();
        }


    } catch (PDOException $e) {
        echo "Falla de Conexion" . $e;
        $html = '
        <div class="alert alert-danger mt-5 mx-5" role="alert">
          Error de Conexion
        </div>
        ';
        die();
    }
}

if (isset($_POST['guardar'])) {
    // busco si el anime ya esta registrado
    $sqlObtenerSerie = "SELECT id, nombre from anime where nombre = :nombre";
    $stmt = $conn->prepare($sqlObtenerSerie);
    $stmt->execute([
        'nombre' => $_POST['nombre']
    ]);

    if ($row = $stmt->fetch()) {
        $idAnime = $row['id'];
        $nombreTemporada = $_POST['temporada'];
    } else {
        $sqlAgregarAnime = "INSERT into anime (nombre, tipo) values (:nombre, :tipo)";
        $stmt = $conn->prepare($sqlAgregarAnime);
        $stmt->execute([
            'nombre' => $_POST['nombre'],
            'tipo' => $_POST['tipo']
        ]);
        $idAnime = $conn->lastInsertId();
        $nombreTemporada = $_POST['nombre'];
    }

    $sqlAgregarTemporada = "INSERT into temporadas (nombre, cant_capitulos, duracion_capitulo, id_anime)
        values (:nombre, :cant_capitulos, :duracion_capitulo, :id_anime)";
    $stmt = $conn->prepare($sqlAgregarTemporada);
    $stmt->execute([
        'nombre' => $nombreTemporada,
        'cant_capitulos' => $_POST['cant_capitulos'],
        'duracion_capitulo' => $_POST['duracion_capitulo'],
        'id_anime' => $idAnime,
    ]);

    $location = 'homeController.php';
}
//TODO: modificar la clase infoAnime para que mapee cada anime y agrege objetos temporada a cada uno
if (isset($_SESSION['user'])) {
    $sqlObtenerSeries = "select a.nombre, 
        case a.tipo 
            when 'S' then 'Serie'
            when 'P' then 'Pelicula'
            when 'O' then 'OVA'
            end as tipo,
        t.nombre as temporada, t.cant_capitulos, t.duracion_capitulo from anime a inner join temporadas t on a.id = t.id_anime
         order by a.nombre;";
    $stmt = $conn->prepare($sqlObtenerSeries);
    $stmt->execute();
    $nombreAnime = null;
    $arrAnimes = [];
    while ($row = $stmt->fetch()) {
        if ($nombreAnime != $row['nombre']) {
            $nombreAnime = $row['nombre'];
            $anime = new InfoAnime($nombreAnime, $row['tipo']);
            $arrAnimes[] = $anime;
        }
        $temporada = new TemporadaAnime($row['temporada'], $row['cant_capitulos'], $row['duracion_capitulo']);
        $anime->addTemporada($temporada);
    }
    $_SESSION['listaAnimes'] = serialize($arrAnimes);
    
    $sqlObtenerStats = "select
    (select count(nombre) from anime where tipo = 'S') as series,
        (select count(nombre) from anime where tipo = 'P') as peliculas,
        (select count(nombre) from anime where tipo = 'O') as ovas,
        count(distinct a.nombre) as cantidad_total,
        sum(t.cant_capitulos) as capitulos
        from anime a inner join temporadas t on a.id = t.id_anime;";
    $stmt = $conn->prepare($sqlObtenerStats);
    $stmt->execute();
    if($row = $stmt->fetch()){
        $arrStats = [
            'series' => $row['series'], 
            'peliculas' => $row['peliculas'], 
            'ovas' => $row['ovas'], 
            'capitulos' => $row['capitulos'],
            'cantidad_total' => $row['cantidad_total']
        ];
    }
    $minutos = null;
    foreach($arrAnimes as $serie){
        $minutos += $serie->totalMinutos;
    }
    $arrStats['total_horas'] = intdiv($minutos, 60).':'. sprintf("%02d",($minutos % 60));
    $arrStats['total_tiempo'] = convertirHorasADiasYHoras($arrStats['total_horas']);


    $_SESSION['statsAnime'] = serialize($arrStats);

    $location = '../home.php';
}

function convertirHorasADiasYHoras($horas) {
    // Calcular los días
    $dias = floor($horas / 24);
    // Calcular las horas restantes
    $horasRestantes = $horas % 24;
    // Retornar el resultado como una cadena de texto
    return $dias . " días, " . $horasRestantes . " horas";
}

header('location:' . $location);