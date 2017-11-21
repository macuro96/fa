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
                $titulo   = trim(filter_input(INPUT_POST, 'titulo'));
                $anyo     = filter_input(INPUT_POST, 'anyo', FILTER_VALIDATE_INT);
                $sipnosis = trim(filter_input(INPUT_POST, 'sipnosis'));
                $duracion = filter_input(INPUT_POST, 'duracion', FILTER_VALIDATE_INT);
                $genero   = trim(filter_input(INPUT_POST, 'genero'));

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
            <input <?= ($bInsercionPosible ? 'disabled' : '') ?> id="titulo" name="titulo" value="<?= h($titulo) ?>" type="text">
            <br>
            <label for="anyo">Año:</label>
            <br>
            <input <?= ($bInsercionPosible ? 'disabled' : '') ?> id="anyo" name="anyo" type="number" value="<?= h($anyo) ?>">
            <br>
            <label for="sipnosis">Sipnosis:</label>
            <br>
            <textarea <?= ($bInsercionPosible ? 'disabled' : '') ?> id="sipnosis" name="sipnosis" value="<?= h($sipnosis) ?>"></textarea>
            <br>
            <label for="duracion">Duración:</label>
            <br>
            <input <?= ($bInsercionPosible ? 'disabled' : '') ?> id="duracion" name="duracion" value="<?= h($duracion) ?>" type="number">
            <br>
            <label for="genero">Género:*</label>
            <br>
            <input <?= ($bInsercionPosible ? 'disabled' : '') ?> id="genero" name="genero" value="<?= h($genero) ?>" type="text">
            <br>
            <br>

            <input type="submit" value="Insertar película">

        </form>

        <?php
        if ($bInsercionPosible && $error == null){
            require_once 'db/dbConfig.php';

            $db = new PDO(DB_DSN, DB_USUARIO, DB_PASSWORD);

            $stm = $db->prepare('INSERT INTO "peliculas" (titulo, sipnosis, anyo, duracion, genero_id)
                                      VALUES (:titulo, :sipnosis, :anyo, :duracion, :genero_id)');
            $stm->bindValue(':titulo', convertirParametroDefault($titulo));
            $stm->bindValue(':sipnosis', convertirParametroDefault($sipnosis));
            $stm->bindValue(':anyo', convertirParametroDefault($anyo));
            $stm->bindValue(':duracion', convertirParametroDefault($duracion));
            $stm->bindValue(':genero_id', convertirParametroDefault($generoId));

            $bInsertar = $stm->execute();

            if ($bInsertar){
                header('Location: index.php');
            } else {
                $error = 'Inserción incorrecta';
            }

        } // if ($bInsercionPosible && $error == null)

        if ($error != null):?>
            <h3><?= h($error) ?></h3>
        <?php endif; ?>

    </body>
</html>
