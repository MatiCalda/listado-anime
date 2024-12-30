<?php
require_once '../../vendor/autoload.php';
require_once '../config.php';
include_once '../model/user.php';
include_once '../model/infoAnime.php';
include_once '../model/temporadaAnime.php';
session_start();
try {
    header('Content-Type: application/json');

    // Obtiene los datos de la solicitud POST
    //$data = json_decode(file_get_contents('php://input'), true);

    if (isset($_SESSION['user'])) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['idAnime'])) {
                $idAnime = $_GET['idAnime'];
                try {
                    $sqlObtenerTemporadas = "select id, nombre, cant_capitulos, duracion_capitulo 
                        from temporadas where id_anime = :id_anime";
                    $stmt = $conn->prepare($sqlObtenerTemporadas);
                    $stmt->execute([
                        "id_anime" => $idAnime
                    ]);
                    $arrTemporadas = [];
                    while ($row = $stmt->fetch()) {
                        $temporada = new TemporadaAnime($row['nombre'], $row['cant_capitulos'], $row['duracion_capitulo'], $row['id']);
                        $arrTemporadas[] = $temporada;
                    }

                    $response = [
                        'message' => 'respuesta correcta',
                        'temporadas' => $arrTemporadas,
                    ];
                } catch (PDOException $e) {
                    $response = [
                        'message' => 'respuesta incorrecta: ' . $e->getMessage(),
                    ];
                }
            } else {
                $response = [
                    'message' => 'No se ha recibido ningún ID'
                ];
            }
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = json_decode(file_get_contents('php://input'), true);
                if($data['anime']['modificar'] == true){
                    $sqlUpdate = "update anime
                        set nombre = :nombre, tipo = :tipo
                        where id = :id";
                    $stmt = $conn->prepare($sqlUpdate);
                    $stmt->execute([
                        "id" => $data['anime']['id'],
                        "nombre" => $data['anime']['nombre'],
                        "tipo" => $data['anime']['tipo']
                    ]);
                }
                foreach ($data['temporadas']['modificar'] as $temporada) {
                    $sqlUpdate = "update temporadas
                set nombre = :nombre, cant_capitulos = :cant_capitulos, duracion_capitulo = :duracion_capitulo
                where id = :id";
                    $stmt = $conn->prepare($sqlUpdate);
                    $stmt->execute([
                        "id" => $temporada['id'],
                        "nombre" => $temporada['nombre'],
                        "cant_capitulos" => $temporada['cantidadCapitulos'],
                        "duracion_capitulo" => $temporada['duracionCapitulo']
                    ]);
                }
                $idsEliminar = implode(', ', $data['temporadas']['eliminar']);
                $sqlDelete = "DELETE from temporadas where id in (:ids)";
                $stmt = $conn->prepare($sqlDelete);
                $stmt->execute([
                    "ids" => $idsEliminar
                ]);
                $response = [
                    'status' => 'success',
                    'message' => 'Modificaciones realizadas correctamente'
                ];
            } catch (PDOException $e) {
                $response = [
                    'status' => 'error',
                    'message' => 'Ha ocurrido un error, intente nuevamente : ' . $e->getMessage()
                ];
            }
        }
        if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            if (isset($_GET['idAnime'])) {
                $idAnime = $_GET['idAnime'];
                try {
                    $eliminarAnime = "DELETE from anime where id = :id";
                    $stmt = $conn->prepare($eliminarAnime);
                    $stmt->execute([
                        "id" => $idAnime
                    ]);
                    
                    $response = [
                        'message' => 'anime eliminado correctamente',
                    ];
                } catch (PDOException $e) {
                    $response = [
                        'message' => 'respuesta incorrecta: ' . $e->getMessage(),
                    ];
                }
            }
        }

    } else {
        $response = [
            'message' => 'No hay usuario logueado'
        ];
    }




} catch (PDOException $e) {
    $response = [
        'message' => 'respuesta incorrecta: ' . $e->getMessage()
    ];
}

echo json_encode($response);


