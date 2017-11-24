<?php session_name('fa'); session_start(); ?>
<?php

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null){
    header('Location: ../index.php');
}

require_once "auxiliar.php";

$aResultadoSQL = borrarPelicula($id);

$bDelete = $aResultadoSQL['success'];
$nRows   = $aResultadoSQL['salida'];

if ($bDelete):
    if ($nRows > 0):
        $_SESSION['mensaje'] = 'La película se ha borrado correctamente';
        header('Location: ../index.php');
    else: ?>
        <h3>No existe la una película con id <?= htmlentities($id) ?></h3>
    <?php endif;

else: ?>
    <h3>No se ha podido realizar el borrado de la fila</h3>
<?php endif;
