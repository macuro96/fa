<?php require_once 'php/G_login.php' ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Login</title>

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">

    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-offset-4 col-lg-4 page-header">
                    <h1>Login</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-4 col-lg-4">                    
                    <form class="form-horizontal form-login" action="login.php" method="post">
                        <div class="form-group">
                            <label for="usuario" class="col-sm-2 control-label">Usuario</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="usuario" name="usuario">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">Contraseña</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label><input type="checkbox"> Recuérdame</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Entrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php mostrarErrores($errores) ?>

        </div>

        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

    </body>

</html>