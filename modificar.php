<?php
require_once "auxiliar.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$bSelect   = false;
$rowSelect = null;
$error     = null;

if (isset($id)){
    $aResultadoSQLPelicula = buscarPelicula($id);

    $bSelect   = $aResultadoSQLPelicula['success'];
    $rowSelect = $aResultadoSQLPelicula['salida'];

} // if (isset($id))

if (!$bSelect || !$rowSelect){
    header('Location: index.php');
} // if (!$bSelect || !$rowSelect)

if (empty($_POST)){
    $titulo       = ($rowSelect->titulo);
    $anyo         = $rowSelect->anyo;
    $sipnosis     = $rowSelect->sipnosis;
    $duracion     = $rowSelect->duracion;
    $generoNombre = $rowSelect->genero_id;

    $aResultadoSQLGenero = buscarGenero($generoNombre);

    $genero = $aResultadoSQLGenero['salida']->nombre;

} else { // if (empty($_POST))
    $titulo   = convertirParametroNull(trim(filter_input(INPUT_POST, 'titulo')));
    $anyo     = convertirParametroNull(filter_input(INPUT_POST, 'anyo', FILTER_VALIDATE_INT));
    $sipnosis = convertirParametroNull(trim(filter_input(INPUT_POST, 'sipnosis')));
    $duracion = convertirParametroNull(filter_input(INPUT_POST, 'duracion', FILTER_VALIDATE_INT));
    $genero   = convertirParametroNull(trim(filter_input(INPUT_POST, 'genero')));

    $aResultadoSQLGenero = filtrarGenero($genero, true);

    $stmGenero     = $aResultadoSQLGenero['salida'];
    $bSelectGenero = $aResultadoSQLGenero['success'];

    if ($bSelectGenero){
        $rowGenero = $stmGenero->fetchObject();

        if ($rowGenero){
            $generoId = $rowGenero->id;

            var_dump($titulo);
            var_dump($anyo);
            var_dump($sipnosis);
            var_dump($duracion);
            var_dump($genero);

            require_once 'db/dbConfig.php';

            $db = new PDO(DB_DSN, DB_USUARIO, DB_PASSWORD);

            $stm = $db->prepare('SELECT * FROM "modificarPelicula"(:id, :titulo, :sipnosis, :anyo, :duracion, :genero_id)');
            $stm->bindValue(':id', $id);
            $stm->bindValue(':titulo', $titulo);
            $stm->bindValue(':sipnosis', $sipnosis);
            $stm->bindValue(':anyo', $anyo);
            $stm->bindValue(':duracion', $duracion);
            $stm->bindValue(':genero_id', $generoId);

            $bModificar = $stm->execute();

            if ($bModificar){
                $bModificadoCorrectamente = $stm->fetchObject();

                if ($bModificadoCorrectamente){
                    header('Location: index.php');
                } else {
                    $error = 'Modificación incorrecta'; // refactorizar
                }

            } else {
                $error = 'Modificación incorrecta';
            }

        } else { // if ($stmGenero->rowCount())
            $error = "El genero $genero no existe";
        } // else ($stmGenero->rowCount())

    } else { // if ($bSelectGenero)
        $error = "No se ha podido realizar la modificación con exito";
    } // else ($bSelectGenero)

} // else (empty($_POST))

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Modificar <?= $titulo ?></title>
    </head>
    <body>

        <form action="modificar.php?id=<?= $id ?>" method="post">
            <label for="titulo">Título:*</label>
            <br>
            <input id="titulo" name="titulo" value="<?= h($titulo) ?>" type="text">
            <br>
            <label for="anyo">Año:</label>
            <br>
            <input id="anyo" name="anyo" type="number" value="<?= h($anyo) ?>">
            <br>
            <label for="sipnosis">Sipnosis:</label>
            <br>
            <textarea id="sipnosis" name="sipnosis" value="<?= h($sipnosis) ?>"></textarea>
            <br>
            <label for="duracion">Duración:</label>
            <br>
            <input id="duracion" name="duracion" value="<?= h($duracion) ?>" type="number">
            <br>
            <label for="genero">Género:*</label>
            <br>
            <input id="genero" name="genero" value="<?= h($genero) ?>" type="text">
            <br>
            <br>

            <input type="submit" value="Modificar película">

        </form>

        <?php if ($error != null):?>
            <h3><?= h($error) ?></h3>
        <?php endif; ?>

    </body>
</html>
