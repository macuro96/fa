<?php namespace php\clases; require_once 'php/load/login.php'; ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php encabezado('Login') ?>
    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-offset-3 col-lg-6 page-header">
                    <h1>Login</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-3 col-lg-6">
                    <form class="form-horizontal form-login" action="login.php" method="post">
                        <div class="form-group">
                            <label for="nombre" class="col-sm-2 control-label">Usuario</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nombre" name="nombre">
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

            <?php if (!empty($aErrores)): ?>
                <div class="row">
                    <div class="col-lg-offset-4 col-lg-3">
                        <?php mostrarErrores($aErrores) ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <?php include 'pie.php' ?>

    </body>

</html>
