<?php

session_name('fa'); session_start();

$error = null;

if (!empty($_POST)){
    $nombre   = filter_input(INPUT_POST, 'nombre');
    $password = filter_input(INPUT_POST, 'password');

    if (isset($nombre, $password)){
        require_once "db/dbConfig.php";

        $db = new PDO(DB_DSN, DB_USUARIO, DB_PASSWORD);
        $stm = $db->prepare('SELECT *
                               FROM "usuarios"
                              WHERE "nombre" = :nombre AND "password" = :password');
        //$stm->bindValue(':password', $);

        $bSelectUsuario = $stm->execute();

        if ($bSelectUsuario){
            $row = $stm->fetchObject();

            //if ($)

        } else {
            $error = 'Ha ocurrido un error y se ha realizado el logeo con exito';
        }

    } else { // if (isset($nombre, $password))
        $error = 'Debe introducir todos los datos';
    } // else (isset($nombre, $password))

} // if (!empty($_POST))

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Login</title>
    </head>
    <body>
        <form action="login.php" method="post">
            <label for="nombre">Nombre:</label>
            <input name="nombre" type="text">
            <br>
            <label for="password">Contraseña:</label>
            <input name="password" type="password">
            <br>
            <input name="recuerdame" type="checkbox">
            <label for="recuerdame">Recordar contraseña</label>
            <br>
            <br>
            <input type="submit" value="Entrar">
        </form>
    </body>
</html>
