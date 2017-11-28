<?php require_once 'php/G_accion-pelicula.php' ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Acción película</title>

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">

    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-offset-1 col-lg-4 page-header">
                    <h1><?= h($accion) . ' película' ?></h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-1 col-lg-8">
                    <form action=<?php gAccionUrl($accion, $sConsultaId) ?> method="post">
                        <div class="form-group">
                            <label for="titulo">Titulo:</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" value="<?= h($titulo) ?>">
                        </div>
                        <div class="form-group">
                            <label for="anyo">Año:</label>
                            <input type="number" class="form-control" id="anyo" name="anyo" value="<?= h($anyo) ?>">
                        </div>
                        <div class="form-group">
                            <label for="sipnosis">Sipnosis:</label>
                            <textarea class="form-control" rows="5" id="sipnosis" name="sipnosis"><?= h($sipnosis) ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="duracion">Duracion:</label>
                            <input type="number" class="form-control" id="duracion" name="duracion" value="<?= h(comprobarPorDefecto($duracion)) ?>">
                        </div>
                        <div class="form-group">
                            <label for="genero">Género:</label>
                            <?php gSelectGeneros($stmGeneros, $genero) ?>

                        </div>
                        <button type="submit" class="btn btn-default"><?= h($accion) ?></button>
                    </form>                    

                </div>

            </div>

            <?php mostrarErrores($errores) ?>
        
        </div>

        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    
    </body>

</html>