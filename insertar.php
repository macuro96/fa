<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Insertar una nueva película</title>
    </head>
    <body>
        <?php
            require_once "auxiliar.php";

            $bInsercionCorrecta = false;
            $titulo = $anyo = $sipnosis = $duracion = $genero = '';

            if (!empty($_POST)){
                $titulo   = trim(filter_input(INPUT_POST, 'titulo'));
                $anyo     = filter_input(INPUT_POST, 'anyo', FILTER_VALIDATE_INT);
                $sipnosis = trim(filter_input(INPUT_POST, 'sipnosis'));
                $duracion = filter_input(INPUT_POST, 'duracion', FILTER_VALIDATE_INT);
                $genero   = trim(filter_input(INPUT_POST, 'genero'));

                $aResultadoSQLGenero = filtrarGenero($genero, true);

                $stmGenero     = $aResultadoSQLGenero['salida'];
                $bSelectGenero = $aResultadoSQLGenero['success'];

                $row = null;

                if ($bSelectGenero):
                    $row = $stmGenero->fetchObject();

                else:

                endif;

            } // if (!empty($_POST))

        ?>

        <form action="<?= (!$bInsercionCorrecta ? 'insertar.php' : 'hacer_borrado.php') ?>" method="post">
            <label for="titulo">Título:*</label>
            <br>
            <input id="titulo" name="titulo" value="<?= $titulo ?>" type="text">
            <br>
            <label for="anyo">Año:</label>
            <br>
            <input id="anyo" name="anyo" type="number" value="<?= $anyo ?>">
            <br>
            <label for="sipnosis">Sipnosis:</label>
            <br>
            <textarea id="sipnosis" name="sipnosis" value="<?= $sipnosis ?>"></textarea>
            <br>
            <label for="duracion">Duración:</label>
            <br>
            <input id="duracion" name="duracion" value="<?= $duracion ?>" type="number">
            <br>
            <label for="genero">Género:*</label>
            <br>
            <input id="genero" name="genero" value="<?= $genero ?>" type="text">
            <br>
            <br>

            <input type="submit" value="Insertar película">

        </form>

    </body>
</html>
