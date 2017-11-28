<?php

/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

require_once 'F_Session.php';
require_once 'F_DB.php';

$tituloBuscador = trim(filter_input(INPUT_GET, 'titulo-buscador'));

try {
    $stmPeliculas = DBbuscadorPelicula($tituloBuscador);
} catch (Exception $e){
    SessionMensajeModificar($e->getMessage());
}

function gBotonSesion()
{
    if (SessionExisteSesionUsuario()):?>
        <div class="col-lg-offset-3 col-lg-1">
            <h4><?= SessionNombreUsuario() ?></h4>
        </div>
        <div class="col-lg-2">
            <a class="btn btn-primary center-block" href="logout.php" role="button">Cerrar sesión</a>
        </div>
    <?php else: ?>
        <div class="col-lg-offset-5 col-lg-2">
            <a class="btn btn-primary center-block" href="login.php" role="button">Iniciar sesión</a>
        </div>
    <?php endif;    

} // function gBotonSesion()

function gTablaPeliculas($stmPeliculas)
{    
    if (isset($stmPeliculas)):?>
        <div class="row">
            <div class="col-lg-offset-3 col-lg-6">
                <table class="table table-bordered table-striped">                                        
                    <thead>
                        <th>Título</th>
                        <th>Año</th>
                        <th>Sipnosis</th>
                        <th>Duración</th>
                        <th>Género</th>
                        <th>Operaciones</th>
                    </thead>
                    <tbody>                            
                        <?php
                        while ($rowPelicula = $stmPeliculas->fetchObject()):?>
                            <tr>
                                <td><?= h($rowPelicula->titulo)     ?></td>
                                <td><?= h($rowPelicula->anyo)       ?></td>
                                <td><?= h($rowPelicula->sipnosis)   ?></td>
                                <td><?= h($rowPelicula->duracion)   ?></td>
                                <td><?= h($rowPelicula->genero)     ?></td> 
                                <td>
                                    <a class="btn btn-primary" href="accion-pelicula.php?accion=Modificar&id=<?= h($rowPelicula->id) ?>" role="button">Modificar</a>
                                    <a class="btn btn-danger"  href="borrar.php?id=<?= h($rowPelicula->id) ?>" role="button">Borrar</a>
                                </td> 
                            </tr>
                        <?php endwhile; // while ($rowPelicula = $stmPeliculas) ?>
                    </tbody>

                </table> <!-- <table class="table table-bordered table-striped"> -->
            </div> <!-- <div class="col-lg-offset-3 col-lg-6"> -->
        </div> <!-- <div class="row"> -->
        
    <?php endif; // if (isset($stmPeliculas))

} // function gTablaPeliculas()