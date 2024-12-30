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
  <link rel="icon" href="img/icon/favicon.ico" type="image/x-icon">
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
      <div class="col-6 col-sm-6 col-md-4 col-lg-2 border"><strong>Total (hs):
        </strong><?php echo $arrStats['total_horas']; ?> hs</div>
      <div class="col-6 col-sm-6 col-md-4 col-lg-3 border"><strong>Total (dias):
        </strong><?php echo $arrStats['total_tiempo']; ?></div>
      <div class="col-4 col-sm-6 col-md-4 col-lg-1 border"><strong>Series: </strong><?php echo $arrStats['series']; ?>
      </div>
      <div class="col-4 col-sm-6 col-md-4 col-lg-2 border"><strong>Peliculas:
        </strong><?php echo $arrStats['peliculas']; ?></div>
      <div class="col-4 col-sm-6 col-md-4 col-lg-1 border"><strong>OVAs: </strong><?php echo $arrStats['ovas']; ?></div>
      <div class="col-6 col-sm-6 col-md-4 col-lg-1 border"><strong>Total:
        </strong><?php echo $arrStats['cantidad_total']; ?></div>
      <div class="col-6 col-sm-6 col-md-4 col-lg-2 border"><strong>Capitulos:
        </strong><?php echo $arrStats['capitulos']; ?></div>
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


  <!-- MODAL EDITOR -->
  <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body container" id="datosAnime">
                    <div class="row anime">
                        <input type="hidden" value=0>
                        <p class="fs-2">Anime</p>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                            <label for="nombreTemporada">Nombre</label>
                            <input type="text" class="form-control inputAnime" id="nombreAnime" disabled>
                        </div>
                        <div class="col-7 col-sm-8 col-md-4 col-lg-4">
                            <label for="">Tipo</label>
                            <select class="form-select inputAnime" aria-label="Default select example" id="tipoAnime"
                                disabled>
                                <option value="Serie">Serie</option>
                                <option value="Pelicula">Pelicula</option>
                                <option value="OVA">OVA</option>
                            </select>
                        </div>
                        <div class="col d-inline-flex align-items-end justify-content-around">
                            <button type="button" class="btn btn-light btn-edit"><i
                                    class="i-edit bi bi-pencil-square"></i></i></button>
                            <button type="button" class="btn btn-info btn-reload"><i
                                    class="i-reload bi bi-arrow-counterclockwise"></i></button>
                        </div>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <div class="modal-body container" id="selected-anime">
                </div>
                <div class="modal-footer d-flex  justify-content-between">
                    <div>
                        <button type="button" class="btn btn-danger" id="btnDeleteAll">Eliminar Anime</button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-success" id="btnGuardar">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL MESSAGE -->
    <div class="modal fade" id="modalMessage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-l">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Informacion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="msgResponse">
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-success" id="btnReload">Aceptar</a>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL CONFIRM DELETE -->
    <div class="modal fade" id="modalConfirm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Atencion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Vas a eliminar el anime: </p>
                    <p><b class="nombre"></b></p>
                    <p>Estas de acuerdo?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="btnConfirnDelete">Si,
                        Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        const btnDeleteAll = document.getElementById('btnDeleteAll')
        btnDeleteAll.addEventListener('click', () => {
            let id = document.getElementById('datosAnime').querySelector('.row.anime').getAttribute('data-bs-id')
            let nombre = document.getElementById('nombreAnime').value
            let modalConfirm = new bootstrap.Modal(document.getElementById('modalConfirm'));
            document.getElementById('modalConfirm').setAttribute('data-bs-id', id)
            document.getElementById('modalConfirm').querySelector('.nombre').textContent = nombre
            modalConfirm.show()
        })
        const btnGuardar = document.getElementById("btnGuardar")
        btnGuardar.addEventListener('click', () => {
            const objAction = {
                anime: {
                    modificar: false
                },
                temporadas: {
                    modificar: [],
                    eliminar: []
                }
            }

            //modal lo toma como id, como tengo un modal con el id=modal, lo reconoce
            const rowAnime = modal.querySelector('.row.anime')
            let action = parseInt(rowAnime.querySelector('input[type=hidden]').value);
            switch (action) {
                case 1:
                    objAction.anime.id = rowAnime.getAttribute('data-bs-id')
                    objAction.anime.nombre = rowAnime.querySelector('#nombreAnime').value
                    objAction.anime.tipo = rowAnime.querySelector('#tipoAnime').value.charAt(0) 
// los values tienen que ser una sola letra, lo pongo asi porque lo modifico para mayor compresion desde la base de datos, 
// como agregue una nueva funcionalidad, me choca y tengo que hacer algo de este estilo
                    objAction.anime.modificar = true
                    break;
                default:
                    break;
            }

            modal.querySelectorAll('.row.temp').forEach(row => {
                let action = parseInt(row.querySelector('input[type=hidden]').value);
                let id = row.getAttribute('data-bs-id')
                temp = {}

                switch (action) {
                    case 1:
                        temp['id'] = id
                        temp['nombre'] = row.querySelector('.name').value
                        temp['cantidadCapitulos'] = row.querySelector('.cantcap').value
                        temp['duracionCapitulo'] = row.querySelector('.durcap').value
                        objAction.temporadas.modificar.push(temp)
                        break;
                    case 2:
                        objAction.temporadas.eliminar.push(id)
                        break;

                    default:
                        break;
                }


            })
            //console.log(JSON.stringify(objAction));

            postAnime(objAction).then(data => {
                document.getElementById('msgResponse').innerText = data.message
                if (data.status == 'success') {
                    document.getElementById('btnReload').setAttribute('href', 'controller/homeController.php')
                }else{
                  document.getElementById('btnReload').addEventListener('click', () => {
                    modalMsg.hide();
                    })
                  }
                  myModal.hide();
                  modalMsg.show();
               // console.log(data.message);
            });
        })
    </script>



    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script>
        var myModal = new bootstrap.Modal(document.getElementById('modal'))
        var modalMsg = new bootstrap.Modal(document.getElementById('modalMessage'));
        var confirmModal = new bootstrap.Modal(document.getElementById('modalConfirm'))
        document.querySelectorAll('.trigger-modal').forEach(anime => {
            anime.addEventListener('click', () => {
                var nombreAnime = anime.getAttribute('data-bs-nombreAnime')
                var idAnime = anime.getAttribute('data-bs-id')
                var tipoAnime = anime.getAttribute('data-bs-tipoAnime')
                document.getElementById('modal').setAttribute('data-bs-nombreAnime', nombreAnime)
                document.getElementById('modal').setAttribute('data-bs-idAnime', idAnime)
                document.getElementById('modal').setAttribute('data-bs-tipoAnime', tipoAnime)
                let modal = document.getElementById('selected-anime')
                modal.innerHTML = "<p class=\"fs-5\">Temporadas</p>";

                getAnime(idAnime).then(data => {
                    data.temporadas.forEach(temp => {
                        modal.innerHTML += `
                    <div class="row temp" data-bs-id="${temp['id']}">
                        <input type="hidden" value=0>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                            <label for="nombreTemporada">Nombre</label>
                            <input type="text" class="form-control name" value="${temp['nombre']}" disabled>
                        </div>
                        <div class="col-3 col-sm-4 col-md-4 col-lg-2">
                            <label for="">Capitulos</label>
                            <input type="number" class="form-control cantcap" value=${temp['cantidadCapitulos']} disabled>
                        </div>
                        <div class="col-3 col-sm-3 col-md-3 col-lg-2">
                            <label for="">Duración</label>
                            <input type="number" class="form-control durcap" value=${temp['duracionCapitulo']} disabled>
                        </div>
                        <div class="col d-inline-flex align-items-end justify-content-around">
                            <button type="button" class="btn btn-light btn-edit"><i class="i-edit bi bi-pencil-square"></i></i></button>
                            <button type="button" class="btn btn-danger btn-delete"><i class="i-delete bi bi-trash"></i></button>
                            <button type="button" class="btn btn-info btn-reload"><i class="i-reload bi bi-arrow-counterclockwise"></i></button>
                        </div>
                        <div class="dropdown-divider"></div>
                    </div>
                    `
                    });
                    myModal.show()
                })
            })
        })
        var modalAnime = document.getElementById('modal')
        modalAnime.addEventListener('show.bs.modal', function (event) {

            var modal = this
            var nombreAnime = modal.getAttribute('data-bs-nombreAnime')
            var tipoAnime = modal.getAttribute('data-bs-tipoAnime')
            var idAnime = modal.getAttribute('data-bs-idAnime')

            modal.querySelector('#nombreAnime').value = nombreAnime
            modal.querySelector('#tipoAnime').value = tipoAnime

            tempsOriginal = {
                'anime': {
                    id: idAnime,
                    nombre: nombreAnime,
                    tipo: tipoAnime,
                },
                'temporadas': []
            };
            const inputAnime = modal.querySelector('.row.anime')
            inputAnime.setAttribute('data-bs-id', idAnime)
            let iconEdit = inputAnime.querySelector('.i-edit')
            inputNombre = inputAnime.querySelector('#nombreAnime')
            selectorTipo = inputAnime.querySelector('#tipoAnime')

            inputAnime.addEventListener('change', () => {
                inputNombre.classList.add('bg-warning')
                selectorTipo.classList.add('bg-warning')
            })
            let inputAccion = inputAnime.querySelector('input[type=hidden]')
            inputAnime.querySelector('.btn-edit').addEventListener('click', () => {
                inputAccion.value = 1
                inputNombre.disabled = !inputNombre.disabled
                selectorTipo.disabled = !selectorTipo.disabled
                if (!inputNombre.disabled) {
                    iconEdit.classList.remove('bi-pencil-square')
                    iconEdit.classList.add('bi-check-circle')
                } else {
                    iconEdit.classList.remove('bi-check-circle')
                    iconEdit.classList.add('bi-pencil-square')
                }
            })
            inputAnime.querySelector('.btn-reload').addEventListener('click', () => {
                inputAccion.value = 0

                inputNombre.disabled = selectorTipo.disabled = true
                inputNombre.value = tempsOriginal.anime.nombre
                selectorTipo.value = tempsOriginal.anime.tipo

                iconEdit.classList.remove('bi-check-circle')
                iconEdit.classList.add('bi-pencil-square')
                inputAnime.querySelector('.btn-edit').classList.remove('visually-hidden')
                inputAnime.querySelectorAll('.inputAnime').forEach(field => {
                    field.disabled = true
                    field.classList.remove("text-decoration-line-through")
                    field.classList.remove("bg-warning")
                })
            })

            modal.querySelectorAll('.row.temp').forEach(input => {
                tempsOriginal.temporadas.push({
                    id: input.getAttribute('data-bs-id'),
                    nombre: input.querySelector('.name').value,
                    cantidadCapitulos: input.querySelector('.cantcap').value,
                    duracionCapitulo: input.querySelector('.durcap').value,
                });
                input.addEventListener('change', () => {
                    input.querySelectorAll('input').forEach(field => {
                        field.classList.add('bg-warning')

                    })
                })
                let inputAccion = input.querySelector('input[type=hidden]')
                let iconEdit = input.querySelector('.i-edit')

                const btnEdit = input.querySelector('.btn-edit')
                btnEdit.addEventListener('click', () => {
                    inputAccion.value = 1
                    input.querySelectorAll('input').forEach(field => {
                        field.disabled = !field.disabled;
                        if (!field.disabled) {
                            iconEdit.classList.remove('bi-pencil-square')
                            iconEdit.classList.add('bi-check-circle')
                        } else {
                            iconEdit.classList.remove('bi-check-circle')
                            iconEdit.classList.add('bi-pencil-square')
                        }
                    });
                })
                const btnDelete = input.querySelector('.btn-delete')
                btnDelete.addEventListener('click', () => {
                    inputAccion.value = 2
                    const temp = tempsOriginal.temporadas.find(temporada => temporada.id === input.getAttribute('data-bs-id'));
                    input.querySelector('.name').value = temp.nombre
                    input.querySelector('.cantcap').value = temp.cantidadCapitulos
                    input.querySelector('.durcap').value = temp.duracionCapitulo
                    input.querySelectorAll('input').forEach(field => {
                        field.disabled = true
                        iconEdit.classList.remove('bi-check-circle')
                        iconEdit.classList.add('bi-pencil-square')
                        btnEdit.classList.add('visually-hidden')
                        field.classList.add("text-decoration-line-through")
                        field.classList.remove("bg-warning")
                    })
                })
                const btnReload = input.querySelector('.btn-reload')
                btnReload.addEventListener('click', () => {
                    inputAccion.value = 0
                    const temp = tempsOriginal.temporadas.find(temporada => temporada.id === input.getAttribute('data-bs-id'));
                    input.querySelector('.name').value = temp.nombre
                    input.querySelector('.cantcap').value = temp.cantidadCapitulos
                    input.querySelector('.durcap').value = temp.duracionCapitulo
                    iconEdit.classList.remove('bi-check-circle')
                    iconEdit.classList.add('bi-pencil-square')
                    btnEdit.classList.remove('visually-hidden')
                    input.querySelectorAll('input').forEach(field => {
                        field.disabled = true
                        field.classList.remove("text-decoration-line-through")
                        field.classList.remove("bg-warning")
                    })
                })
            });
        })
        modalAnime.addEventListener('hide.bs.modal', function (event) {
            var modal = this
            //reseteo los datos del modal
            modal.querySelector('#datosAnime').innerHTML = `
            <div class="row anime">
                <input type="hidden" value=0>
                <p class="fs-2">Anime</p>
                <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                    <label for="nombreTemporada">Nombre</label>
                    <input type="text" class="form-control inputAnime" id="nombreAnime" disabled>
                </div>
                <div class="col-7 col-sm-8 col-md-4 col-lg-4">
                    <label for="">Tipo</label>
                    <select class="form-select inputAnime" aria-label="Default select example" id="tipoAnime"
                        disabled>
                        <option value="Serie">Serie</option>
                        <option value="Pelicula">Pelicula</option>
                        <option value="OVA">OVA</option>
                    </select>
                </div>
                <div class="col d-inline-flex align-items-end justify-content-around">
                    <button type="button" class="btn btn-light btn-edit"><i
                            class="i-edit bi bi-pencil-square"></i></i></button>
                    <button type="button" class="btn btn-info btn-reload"><i
                            class="i-reload bi bi-arrow-counterclockwise"></i></button>
                </div>
                <div class="dropdown-divider"></div>
            </div>
            `

        })
        var modalConfirmar = document.getElementById('modalConfirm');
        modalConfirmar.addEventListener('show.bs.modal', function (event) {
            let modalConfirm = this
            let idAnime = modalConfirm.getAttribute('data-bs-id')
            modalConfirm.querySelector('#btnConfirnDelete').addEventListener('click', () => {
                deleteAnime(idAnime).then(data => {
                    document.getElementById('msgResponse').innerText = data.message
                    if (data.status == 'success') {
                        document.getElementById('btnReload').setAttribute('href', 'controller/homeController.php')
                    }
                });
                myModal.hide();
                modalMsg.show();

            })
        })


        async function getAnime(idAnime) {
            let respuesta;

            const response = await fetch(`controller/infoAnimeController.php?idAnime=${encodeURI(idAnime)}`, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            });

            const data = await response.json();
            respuesta = data;
            return respuesta;
        }
        async function postAnime(animeData) {
            const response = await fetch('controller/infoAnimeController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(animeData)
            });

            if (!response.ok) {
                throw new Error('Error en la solicitud: ' + response.status);
            }

            const data = await response.json();
            return data;
        }
        async function deleteAnime(idAnime) {
            let respuesta;

            const response = await fetch(`controller/infoAnimeController.php?idAnime=${encodeURI(idAnime)}`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' }
            });

            const data = await response.json();
            respuesta = data;
            return respuesta;
        }
    </script>
  
</body>

</html>