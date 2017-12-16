<?php namespace php\clases; include_once 'php/load/accion.php'; ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php encabezado("Acción $sModo"); ?>
    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-offset-1 col-lg-4 page-header">
                    <h1><?= h($accion) ?> <?= $sModo ?></h1>
                </div>
                <?php logeo($oUsuario->getNombre()) ?>
            </div>

            <div class="row">
                <div class="col-lg-offset-1 col-lg-8">
                    <form action="accion.php?modo=<?= $sModo ?>&accion=<?= h($accion) ?><?= isset($sConsultaId) ? h($sConsultaId) : '' ?>" method="post">
                        <?php
                        if ($sModo == MODO_PELICULAS): ?>
                            <div class="form-group">
                                <label for="titulo">Titulo:</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" value="<?= h($sTitulo) ?>">
                            </div>
                            <div class="form-group">
                                <label for="anyo">Año:</label>
                                <input type="number" class="form-control" id="anyo" name="anyo" value="<?= h($iAnyo) ?>">
                            </div>
                            <div class="form-group">
                                <label for="sipnosis">Sipnosis:</label>
                                <textarea class="form-control" rows="5" id="sipnosis" name="sipnosis"><?= h($sSipnosis) ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="duracion">Duracion:</label>
                                <input type="number" class="form-control" id="duracion" name="duracion" value="<?= h($iDuracion) ?>">
                            </div>
                            <div class="form-group">
                                <label for="genero">Género:</label>
                                <select class="form-control" id="genero" name="genero_id">
                                    <?php
                                    for ($g = 0; $g < count($aGenerosAll); $g++):
                                        $idGenero     = $aGenerosAll[$g]['id'];
                                        $nombreGenero = $aGenerosAll[$g]['nombre']; ?>

                                        <option <?= ($idGenero === $iGenero_id ? 'selected' : '') ?> value="<?= h($idGenero) ?>"><?= h($nombreGenero) ?></option>

                                    <?php endfor; ?>
                                </select>
                            </div>
                        <?php elseif ($sModo == MODO_GENEROS): ?>
                            <div class="form-group">
                                <label for="titulo">Nombre:</label>
                                <input type="text" class="form-control" id="nombre-genero" name="nombre-genero" value="<?= h($sNombreGenero) ?>">
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-default"><?= h($accion) ?></button>
                    </form>

                </div>

            </div>

            <?php if (!empty($aErrores)): ?>
                <div class="row">
                    <div class="col-lg-offset-2 col-lg-3">
                        <?php mostrarErrores($aErrores) ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <?php include 'pie.php' ?>

    </body>

</html>
