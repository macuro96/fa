<?php
/**
 * @author Manuel Cuevas Rodriguez
 * @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
 * @license https://www.gnu.org/licenses/gpl.txt
 */

namespace php\clases;

require_once 'auth.php';
require_once 'php/autoload.php';

use \Exception;

$sPassword     = input(INPUT_POST, 'password');
$sConfPassword = input(INPUT_POST, 'conf-password');

$oUsuario = unserialize(Sesion::usuarioSesion());
$aErrores = [];

try {
    if (!empty($_POST)){
        $aErrores = Usuario::validar([
            'password' => $sPassword,
            'password' => $sConfPassword,
            'confirmarPassword' => [
                'password'          => $sPassword,
                'confirmarPassword' => $sConfPassword
            ]
        ]);

        exceptionErrores($aErrores);

        $oUsuario->modificarPassword($sPassword);
        Sesion::volverIndexMensaje('Se ha modificado la contraseña correctamente');

    } // if (!empty($_POST))

} catch (Exception $e){
    if (empty($aErrores)){
        Sesion::volverIndexMensaje($e);
    }

} // try
