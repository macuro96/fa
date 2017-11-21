<?php

require_once "auxiliar.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); // Validar

if ($id === false || $id === null){
    header('Location: ../index.php');
}

$aResultadoSQL = buscarPelicula($id);

$row     = $aResultadoSQL['salida'];
$bSelect = $aResultadoSQL['success'];

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Confirmación de borrado</title>
    </head>
    <body>
        <form action="hacer_borrado.php" method="post">
            <input type="hidden" name="id" value="<?= htmlentities($id) ?>">
            <?php
            if ($bSelect && $row):?>
                <h5>¿Seguro que quieres borrar la fila <?= htmlentities($id) ?>, con titulo "<b><?= $row->titulo ?></b>"</h5>
                <br>
                <input type="submit" value="Si">
                <input onclick="window.location.href = '../index.php'" type="button" value="No">

            <?php else: ?>
                <label for="enviar">La fila numero <b><?= htmlentities($id) ?></b> no existe.</label>
                <br>
                <input onclick="window.location.href = '../index.php'" type="button" value="Volver">

            <?php endif; ?>
        </form>
    </body>
</html>
