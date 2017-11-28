<?php require_once 'php/G_borrar.php' ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Confirmar borrado</title>

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">

    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-offset-1 col-lg-4 page-header">
                    <h1>Confirmar borrado</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-4 col-lg-5">
                    <h3>¿Está seguro que quiere borrar la película <b>"<?= h($titulo) ?></b>"?</h3>

                    <form action="hacer-borrado.php" method="post">
                        <input type="hidden" name="id" value="<?= h($id) ?>">
                        <button type="submit" class="btn btn-default">Si</button>
                        <a class="btn btn-primary" href="index.php" role="button">No</a>
                    </form>                                        

                </div>

            </div>

        </div>        

        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    
    </body>

</html>