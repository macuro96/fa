<?php namespace php\clases; include_once 'php/load/cambiar-nombre.php'; ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php encabezado('Cambiar nombre'); ?>
    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-offset-3 col-lg-6 page-header">
                    <h1>Cambiar nombre</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-3 col-lg-6">
                    <form class="form-horizontal form-login" method="post">
                        <div class="form-group">
                            <label for="nombre" class="col-sm-2 control-label">Nuevo nombre</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nombre" name="nombre">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="conf-nombre" class="col-sm-2 control-label">Confirmar nombre</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="conf-nombre" name="conf-nombre">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Cambiar</button>
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
