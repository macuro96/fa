<?php namespace php\clases; require 'php/load/index.php'; ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php encabezado('Inicio') ?>
    </head>

    <body>
        <div class="container-fluid">
            <?php Sesion::notificacionMensaje() ?>

            <div class="row">
                <div class="col-lg-offset-1 col-lg-4 page-header">
                    <h1>Film Affinity</h1>
                </div>
                <?php logeo($sNombreUsuario) ?>
            </div>

            <div class="row">
                <div class="col-lg-offset-1 col-lg-10">
                    <form action="index.php" method="get">
                        <input type='hidden' id="modo" name="modo" value="<?= h($sModo) ?>">
                        <input type='hidden' id="pag-actual" name="pag-actual" value="<?= h($iPaginaActual) ?>">
                        <div class="form-group">
                            <select id="filtro" name="filtro" class="form-control">
                                <?php
                                if ($sModo == MODO_PELICULAS): ?>
                                    <?php generarOption('Titulo', 'titulo', $sFiltro) ?>
                                    <?php generarOption('Año', 'anyo', $sFiltro) ?>
                                    <?php generarOption('Sipnosis', 'sipnosis', $sFiltro) ?>
                                    <?php generarOption('Duración', 'duracion', $sFiltro) ?>
                                    <?php generarOption('Genero', 'genero', $sFiltro) ?>
                                <?php elseif ($sModo == MODO_GENEROS): ?>
                                    <?php generarOption('Nombre', 'nombre', $sFiltro) ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cadena-buscador">Buscador:</label>
                            <input type="text" class="form-control" id="cadena-buscador" name="cadena-buscador" value="<?= h($sCadenaBuscador) ?>">
                        </div>
                        <button type="submit" class="btn btn-default">Buscar</button>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-5 col-lg-2">
                    <a class="btn btn-primary center-block" href="?modo=<?= h($sModoEnlace) ?>" role="button">Cambiar a modo <?= h($sModoEnlace) ?></a>
                    <a class="btn btn-default center-block" href="accion.php?modo=<?= h($sModo) ?>&accion=Insertar" role="button">Insertar nuevo registro</a>
                </div>
            </div>

            <?php
            if ($sModo == MODO_PELICULAS): ?>
                <div class="row">
                    <div class="col-lg-offset-2 col-lg-8">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th><?= enlaceOrden('Título', 'titulo', $sDireccionCambiar, $sModo) ?></th>
                                <th><?= enlaceOrden('Año', 'anyo', $sDireccionCambiar, $sModo) ?></th>
                                <th><?= enlaceOrden('Sipnosis', 'sipnosis', $sDireccionCambiar, $sModo) ?></th>
                                <th><?= enlaceOrden('Duración', 'duracion', $sDireccionCambiar, $sModo) ?></th>
                                <th><?= enlaceOrden('Género', 'genero', $sDireccionCambiar, $sModo) ?></th>
                                <th>Operaciones</th>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($aPeliculas)):
                                    for ($p = 0; $p < count($aPeliculas); $p++): ?>
                                        <tr>
                                            <td><?= h($aPeliculas[$p]['titulo'])                ?></td>
                                            <td><?= h($aPeliculas[$p]['anyo'])                  ?></td>
                                            <td><?= h($aPeliculas[$p]['sipnosis'])              ?></td>
                                            <td><?= h($aPeliculas[$p]['duracion'])              ?></td>
                                            <td><?= h($aPeliculas[$p]['genero']['nombre'])      ?></td>
                                            <td>
                                                <a class="btn btn-primary" href="accion.php?modo=<?= $sModo ?>&accion=Modificar&id=<?= h($aPeliculas[$p]['id']) ?>" role="button">Modificar</a>
                                                <a class="btn btn-danger"  href="borrar.php?modo=<?= $sModo ?>&id=<?= h($aPeliculas[$p]['id']) ?>" role="button">Borrar</a>
                                            </td>
                                        </tr>
                                    <?php
                                    endfor; // for ($p = 0; $p < count($aPeliculas); $p++)
                                endif; // if (isset($aPeliculas))
                                ?>
                            </tbody>

                        </table> <!-- <table class="table table-bordered table-striped"> -->
                    </div> <!-- <div class="col-lg-offset-3 col-lg-6"> -->
                </div> <!-- <div class="row"> -->

            <?php elseif ($sModo == MODO_GENEROS): ?>
                <div class="row">
                    <div class="col-lg-offset-4 col-lg-4">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th><?= enlaceOrden('Nombre', 'nombre', $sDireccionCambiar, $sModo) ?></th>
                                <th>Operaciones</th>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($aGeneros)):
                                    for ($p = 0; $p < count($aGeneros); $p++): ?>
                                        <tr>
                                            <td><?= h($aGeneros[$p]['nombre'])?></td>
                                            <td>
                                                <a class="btn btn-primary" href="accion.php?modo=<?= h($sModo) ?>&accion=Modificar&id=<?= h($aGeneros[$p]['id']) ?>" role="button">Modificar</a>
                                                <a class="btn btn-danger"  href="borrar.php?modo=<?= h($sModo) ?>&id=<?= h($aGeneros[$p]['id']) ?>" role="button">Borrar</a>
                                            </td>
                                        </tr>
                                    <?php
                                    endfor; // for ($p = 0; $p < count($aGeneros); $p++)
                                endif; // if (isset($aGeneros))
                                ?>
                            </tbody>

                        </table> <!-- <table class="table table-bordered table-striped"> -->
                    </div> <!-- <div class="col-lg-offset-3 col-lg-6"> -->
                </div> <!-- <div class="row"> -->

            <?php endif; ?>

            <?php
            if ($iTotalElementos > 0): ?>
                <div class="row">
                    <div class="col-lg-offset-3 col-lg-6 paginador">
                        <?php paginador($iPaginaActual, $iMaxElementosPorPagina, $iTotalElementos) ?>
                    </div>
                </div>
            <?php endif; ?>

        </div> <!-- <div class="container-fluid"> -->

        <?php include 'pie.php' ?>

    </body>

</html>
