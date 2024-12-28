<?php
include_once 'user.php';


class NavBar{
    public $paginaActiva;
    public $user;
    
    public function __construct($paginaActiva, $user){
        $this -> paginaActiva = $paginaActiva;
        $this -> user = $user;
        
        
    }
    
    public function render(){
        $html = '
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
              <a class="navbar-brand" href="controller/homeController.php">AnimeList</a>
                <div class="d-flex flex-row-reverse nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: rgba(255,255,255,.55);">
                        <label for="" class="text-light">'.$this->user->nombre.'</label>
                        <img src="'.(($this->user->picture)?$this->user->picture:'img/usericon.png').'" alt="mdo" width="32" height="32" class="rounded-circle">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Perfil</a></li>
                        <li><a class="dropdown-item" href="#">Configuracion</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="index.php?logout">Salir</a></li>
                    </ul>
                </div>
            </div>
          </nav>
        ';
        
        echo $html;
    }
    
    
}