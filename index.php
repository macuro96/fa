<?php require_once 'php/G_index.php'; ?>
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
                <?php gBotonSesion(); ?>
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

            <?php gTablaPeliculas($stmPeliculas) ?>

        </div>

        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

    </body>

</html>