<?php
require_once 'php/F_Session.php';
SessionCrear();

require_once 'php/F_DB.php';

$errores = array();

$nombre   = trim(filter_input(INPUT_POST, 'usuario'));
$password = trim(filter_input(INPUT_POST, 'password'));

if (!empty($_POST)){
    try {
        validar([
            'nombreUsuario'   => $nombre,
            'passwordUsuario' => $password
        ], $errores);

        $rowUsuario        = DBbuscarUsuario($nombre, $password)['salida'];
        $bPasswordCorrecto = false;

        try {
            if (!$rowUsuario){
                throw new Exception('El nombre y/o contraseña no coincide con ningún usuario');
            } // if (!$rowUsuario)

            $passwordVerificar = $rowUsuario->password;
            $bPasswordCorrecto = password_verify($password, $passwordVerificar);

            $idUsuario = $rowUsuario->id;
            
            if (!$bPasswordCorrecto){
                throw new Exception('El nombre y/o contraseña no coincide con ningún usuario');
            } // if (!$bPasswordCorrecto)

            SessionIniciarSesionUsuario($idUsuario, $nombre);

            SessionMensajeModificar('Autenticación realizada con éxito');
            header('Location: index.php');

        } catch (Exception $err){
            $errores[] = $err->getMessage();
        }

    } catch (Exception $e){
        $mensaje = $e->getMessage();

        if ($mensaje != null){
            SessionMensajeModificar($e->getMessage());
            header('Location: index.php');   

        } // if ($mensaje != null)

    } // catch (Exception $e)

} // if (!empty($_POST))

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Login</title>

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">

    </head>

    <body>
        <div class="container-fluid">
            <?php notificacionMensaje() ?>

            <div class="row">
                <div class="col-lg-offset-4 col-lg-4 page-header">
                    <h1>Login</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-offset-4 col-lg-4">                    
                    <form class="form-horizontal form-login" action="login.php" method="post">
                        <div class="form-group">
                            <label for="usuario" class="col-sm-2 control-label">Usuario</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="usuario" name="usuario">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">Contraseña</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label><input type="checkbox"> Recuérdame</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Entrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php
            if (!empty($errores)):?>
                <div class="row">
                    <div class="col-lg-offset-4 col-lg-4">
                        <?php foreach ($errores as $error):?>
                            <h4><?= $error ?></h4>                        
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

    </body>

</html>