<?php

require_once '../vendor/autoload.php';
require_once 'config.php';
session_start();
if (isset($_GET['logout'])) {
    // clear the session variable, display logged out message
    // remove all session variables
    session_unset();
    // destroy the session
    session_destroy();
    header('location: index.php');
}
if (isset($_SESSION['user'])) {
    header('location: home.php');
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <?php
    if (isset($_GET['external']) && $_GET['external'] == "true") {
        ?>
        <meta http-equiv="refresh" content="0; url=<?= $client->createAuthUrl() ?>">
        <?php
        exit;
    }
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="icon" href="img/icon/favicon.ico" type="image/x-icon">
    <title>AnimeList</title>
</head>

<body>
    <div class="d-grid gap-2 col-6 mx-auto pt-5">
        <a href="<?= $client->createAuthUrl() ?>" class="btn btn-success" type="button">LogIn</a>
        <!-- <a href="controller/homeController.php" class="btn btn-success" type="button">LogIn</a> -->
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