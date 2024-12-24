<?php
require_once '../../vendor/autoload.php';
require_once '../config.php';
include_once '../model/user.php';
include_once '../model/infoAnime.php';
session_start();

// TODO: Eliminar - solo uso en local
$user = new User();
$user->nombre = "matias";
$_SESSION['user'] = serialize($user);
// TODO: fin Eliminar - solo uso en local

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);
    
    // get profile info
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $correo =  $google_account_info->email;
    $nombre =  $google_account_info->name;
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
        $sqlAgregarTemporada = "INSERT into temporadas (nombre, cant_capitulos, duracion_capitulo, id_anime)
        values (:nombre, :cant_capitulos, :duracion_capitulo, :id_anime)";
        $stmt = $conn->prepare($sqlAgregarTemporada);
        $stmt->execute([
            'nombre' => $_POST['temporada'],
            'cant_capitulos'=> $_POST['cant_capitulos'],
            'duracion_capitulo'=> $_POST['duracion_capitulo'], 
            'id_anime'=> $row['id'], 
        ]);
    }else{
        $sqlAgregarAnime = "INSERT into anime (nombre) values (:nombre)";
        $stmt = $conn->prepare($sqlAgregarAnime);
        $stmt->execute([
            'nombre' => $_POST['nombre']
        ]);
        $idAnime = $conn->lastInsertId();

        $sqlAgregarTemporada = "INSERT into temporadas (nombre, cant_capitulos, duracion_capitulo, id_anime)
        values (:nombre, :cant_capitulos, :duracion_capitulo, :id_anime)";
        $stmt = $conn->prepare($sqlAgregarTemporada);
        $stmt->execute([
            'nombre' => $_POST['nombre'],
            'cant_capitulos'=> $_POST['cant_capitulos'],
            'duracion_capitulo'=> $_POST['duracion_capitulo'], 
            'id_anime'=> $idAnime, 
        ]);
    }

    header('location: homeController.php');
}
//TODO: modificar la clase infoAnime para que mapee cada anime y agrege objetos temporada a cada uno
if (isset($_SESSION['user'])) {
    $sql = "SELECT nombre, tipo, temporadas, cantidad_capitulos, duracion_capitulo, total_minutos, TIME_FORMAT(total_horas, \"%H:%i\") AS total_horas FROM lista ORDER BY nombre ASC";

    $stmt = $conn->prepare($sql);
    // Especificamos el fetch mode antes de llamar a fetch()
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    // Ejecutamos
    $stmt->execute();
    $arrInfoAnime = [];

    while ($row = $stmt->fetch()) { // el usuario existe
        $infoAnime = new InfoAnime($row['nombre'], $row['tipo'], $row['temporadas'], $row['cantidad_capitulos'], $row['duracion_capitulo'], $row['total_minutos'], $row['total_horas']);
        $arrInfoAnime[] = $infoAnime;
    }
    $_SESSION['listaAnimes'] = serialize($arrInfoAnime);

    $sql = "SELECT
	(select count(nombre) from lista where tipo = 'Serie') as series,
	(select count(nombre) from lista where tipo = 'Pelicula') as peliculas,
	(select count(nombre) from lista where tipo = 'OVA') as ovas,
    count(nombre) as cantidad_total,
    SUM(cantidad_capitulos) as capitulos,
    TIME_FORMAT( (SEC_TO_TIME(SUM(TIME_TO_SEC(total_horas)))) , \"%H:%i\") as total_horas, 
	CONCAT(FLOOR(SUM(TIME_TO_SEC(total_horas)) / 86400), ' días, ', 
	MOD(FLOOR(SUM(TIME_TO_SEC(total_horas)) / 3600), 24), ' horas') AS total_tiempo
    from lista;";

    $stmt = $conn->prepare($sql);
    // Especificamos el fetch mode antes de llamar a fetch()
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    // Ejecutamos
    $stmt->execute();
    
    if ($row = $stmt->fetch()) {
        $arrStats = [
            'series' => $row['series'], 
            'peliculas' => $row['peliculas'], 
            'ovas' => $row['ovas'], 
            'capitulos' => $row['capitulos'],
            'cantidad_total' => $row['cantidad_total'], 
            'total_horas' => $row['total_horas'], 
            'total_tiempo' => $row['total_tiempo']
        ];
    }


    $_SESSION['statsAnime'] = serialize($arrStats);
    header('location: ../home.php');
}