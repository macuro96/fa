<?php
/**
 * @author Manuel Cuevas Rodriguez
 * @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
 * @license https://www.gnu.org/licenses/gpl.txt
 */

namespace php\clases;

require_once 'php/autoload.php';
use \Exception;

$aErrores = [];

$sNombre   = input(INPUT_POST, 'nombre');
$sPassword = input(INPUT_POST, 'password');

try {
    if (Sesion::existeSesionUsuario()){
        throw new Exception(Usuario::ERROR_SESION_INICIADA);
    }

    if (!empty($_POST)){
        $aErrores = Usuario::validar([
            'nombre'   => $sNombre,
            'password' => $sPassword
        ]);
        exceptionErrores($aErrores);

        $oUsuario = new Usuario($sNombre, $sPassword);

        try {
            $oUsuario->iniciarSesion();
            header('Location: index.php');
            exit;

        } catch (Exception $e){
            $aErrores[] = $e->getMessage();
            throw new Exception;

        } // try

    } // if (!empty($_POST))

} catch (Exception $e){
    if (empty($aErrores)){
        Sesion::volverIndexMensaje($e);
    }

} // try
