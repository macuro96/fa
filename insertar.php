<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Insertar una nueva película</title>
    </head>
    <body>
        <?php
            require_once "auxiliar.php";

            $bInsercionPosible = false;
            $rowGenero          = null;

            $error = null;

            $titulo = $anyo = $sipnosis = $duracion = $genero = null;

            if (!empty($_POST)){
                $titulo   = convertirParametroNull(trim(filter_input(INPUT_POST, 'titulo')));
                $anyo     = convertirParametroNull(filter_input(INPUT_POST, 'anyo', FILTER_VALIDATE_INT));
                $sipnosis = convertirParametroNull(trim(filter_input(INPUT_POST, 'sipnosis')));
                $duracion = convertirParametroNull(filter_input(INPUT_POST, 'duracion', FILTER_VALIDATE_INT));
                $genero   = convertirParametroNull(trim(filter_input(INPUT_POST, 'genero')));

                $aResultadoSQLGenero = filtrarGenero($genero, true);

                $stmGenero     = $aResultadoSQLGenero['salida'];
                $bSelectGenero = $aResultadoSQLGenero['success'];

                if ($bSelectGenero){
                    $rowGenero          = $stmGenero->fetchObject();
                    $bInsercionPosible  = ($rowGenero == true);

                    if ($bInsercionPosible){
                        $generoId = $rowGenero->id;
                    }

                } // if ($bSelectGenero)

                if (!$bInsercionPosible){
                    $error = 'Los parámetros para la inserción no son correctos.';
                } // if (!$bInsercionPosible)

            } // if (!empty($_POST))

        ?>

        <form action="insertar.php" method="post">
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

            <input type="submit" value="Insertar película">

        </form>

        <?php
        if ($bInsercionPosible && $error == null){
            require_once 'db/dbConfig.php';

            $db = new PDO(DB_DSN, DB_USUARIO, DB_PASSWORD);

            var_dump($duracion);

            $stm = $db->prepare('SELECT * FROM "insertarPelicula"(:titulo, :sipnosis, :anyo, :duracion, :genero_id)');
            $stm->bindValue(':titulo', $titulo);
            $stm->bindValue(':sipnosis', $sipnosis);
            $stm->bindValue(':anyo', $anyo);
            $stm->bindValue(':duracion', $duracion);
            $stm->bindValue(':genero_id', $generoId);

            $bInsertar = $stm->execute();

            if ($bInsertar){
                $bInsertadoCorrectamente = $stm->fetchObject();

                if ($bInsertadoCorrectamente){
                    header('Location: index.php');
                } else {
                    $error = 'Inserción incorrecta'; // refactorizar
                }

            } else {
                $error = 'Inserción incorrecta';
            }

        } // if ($bInsercionPosible && $error == null)

        if ($error != null):?>
            <h3><?= h($error) ?></h3>
        <?php endif; ?>

    </body>
</html>
