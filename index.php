<?php session_name('fa'); session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Listado de películas</title>
    </head>
    <body>
        <?php
        require_once "auxiliar.php";

        $titulo = (filter_input(INPUT_GET, 'titulo') ?? '');

        $aResultadoSQL = filtrarPelicula($titulo);

        $stm     = $aResultadoSQL['salida'];
        $bSelect = $aResultadoSQL['success'];

        if (isset($_SESSION['mensaje'])){?>
            <h3><?= $_SESSION['mensaje'] ?></h3>

        <?php unset($_SESSION['mensaje']);
        } // if (isset($_SESSION['mensaje']))

        if (!isset($_SESSION['Usuario'])):?>
            <a href="login.php">Login</a>
            <br>
            <br>
        <?php endif; ?>

        <form action="#" method="get">
            <input value="<?= htmlentities($titulo) ?>" name="titulo" type="text">
            <input type="submit" value="Buscar">
        </form>

        <br>

        <?php
        if ($bSelect):?>
            <table border="1">
                <thead>
                    <th>Titulo</th>
                    <th>Año</th>
                    <th>Sipnosis</th>
                    <th>Duración</th>
                    <th>Genero</th>
                    <th>Operaciones</th>
                </thead>

                <tbody>
                    <?php
                    while ($row = $stm->fetchObject()):?>
                        <tr>
                            <td><?= $row->titulo ?></td>
                            <td><?= $row->anyo ?></td>
                            <td><?= $row->sipnosis ?></td>
                            <td><?= $row->duracion ?></td>
                            <td><?= $row->genero_id ?></td>
                            <td>
                                <a href="borrar.php?id=<?= $row->id ?>">Borrar</a>
                                <a href="modificar.php?id=<?= $row->id ?>">Modificar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>

            </table>

            <br>

            <a href="insertar.php">Insertar nueva película</a>

        <?php else: ?>
            <h3>No se han encontrado resultados</h3>
        <?php endif; ?>

    </body>
</html>
