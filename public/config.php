<?php
// CONFIGURACION DE TIMEZONE
date_default_timezone_set('America/Argentina/Buenos_Aires');

// CONFIGURACION DE GOOGLE
$clientID = 'id del cliente al configurar con gogle';
$clientSecret = 'secreto del cliente';
$redirectUri = 'https://tuURL/controller/homeController.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
// $client->addScope("picture");


$hostname = "localhost";
$username = "";
$password = "";
$database = "";  

try {
    $conn = new PDO("mysql:host=$hostname;dbname=$database;", $username, $password);
    #ACTIVAMOS LOS ERRORES Y LAS EXCEPTIONES
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Falla de Conexion" . $e;
}

