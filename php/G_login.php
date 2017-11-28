<?php

/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

require_once 'php/F_Session.php';
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

        try {
            $rowUsuario = DBusuario($nombre, $password);

            $idUsuario = $rowUsuario->id;

            SessionIniciarSesionUsuario($idUsuario, $nombre);
            header('Location: index.php');
            exit;

        } catch (Exception $err){
            $errores[] = $err->getMessage();
        }

    } catch (Exception $e){
        $mensaje = $e->getMessage();

        if ($mensaje != null){
            volverIndexMensaje($e);
        } // if ($mensaje != null)

    } // catch (Exception $e)

} // if (!empty($_POST))
