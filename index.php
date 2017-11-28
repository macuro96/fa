<?php
require_once 'php/F_Session.php';
SessionCrear();

require_once 'php/F_DB.php';

$tituloBuscador = trim(filter_input(INPUT_GET, 'titulo-buscador'));

try {
    $aResultadoSQLPeliculas = DBbuscarPeliculaTitulo($tituloBuscador, true);
    $stmPeliculas           = $aResultadoSQLPeliculas['salida'];

} catch (Exception $e){
    SessionMensajeModificar($e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Principal</title>

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">

    </head>

    <body>
        <div class="container-fluid">
            <?php notificacionMensaje() ?>

            <div class="row">
                <div class="col-lg-offset-1 col-lg-4 page-header">
                    <h1>Film Affinity</h1>
                </div>
                <?php
                if (SessionExisteSesionUsuario()):?>
                    <div class="col-lg-offset-5 col-lg-2">
                        <a class="btn btn-primary center-block" href="logout.php" role="button">Cerrar sesión</a>
                    </div>
                <?php else: ?>
                    <div class="col-lg-offset-5 col-lg-2">
                        <a class="btn btn-primary center-block" href="login.php" role="button">Iniciar sesión</a>
                    </div>
                <?php endif; ?>                
            </div>

            <div class="row">
                <div class="col-lg-offset-1 col-lg-10">                    
                    <form action="index.php" method="get">
                        <div class="form-group">
                            <label for="titulo-buscador">Titulo:</label>
                            <input type="text" class="form-control" id="titulo-buscador" name="titulo-buscador" value="<?= h($tituloBuscador) ?>">
                        </div>
                        <button type="submit" class="btn btn-default">Buscar</button>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-5 col-lg-2">
                    <a class="btn btn-default center-block" href="accion-pelicula.php?accion=Insertar" role="button">Insertar nueva película</a>
                </div>
            </div>

            <?php
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
                                            <a class="btn btn-primary" href="accion-pelicula.php?accion=Modificar&id=<?=     h($rowPelicula->id) ?>" role="button">Modificar</a>
                                            <a class="btn btn-danger"  href="borrar.php?id=<?= h($rowPelicula->id) ?>" role="button">Borrar</a>
                                        </td> 
                                    </tr>
                                <?php endwhile; // while ($rowPelicula = $stmPeliculas) ?>
                            </tbody>

                        </table> <!-- <table class="table table-bordered table-striped"> -->
                    </div> <!-- <div class="col-lg-offset-3 col-lg-6"> -->
                </div> <!-- <div class="row"> -->
                
            <?php endif; // if (isset($stmPeliculas)) ?>

        </div>

        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

    </body>

</html>