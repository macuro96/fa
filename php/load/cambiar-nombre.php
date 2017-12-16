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

$sNombre     = input(INPUT_POST, 'nombre');
$sConfNombre = input(INPUT_POST, 'conf-nombre');

$oUsuario = unserialize(Sesion::usuarioSesion());
$aErrores = [];

try {
    if (!empty($_POST)){
        $aErrores = Usuario::validar([
            'nombre' => $sNombre,
            'nombre' => $sConfNombre,
            'confirmarNombre' => [
                'nombre'          => $sNombre,
                'confirmarNombre' => $sConfNombre
            ]
        ]);

        exceptionErrores($aErrores);

        $oUsuario->modificarNombre($sNombre);

        Sesion::cerrarSesionUsuario();
        Sesion::iniciarSesionUsuario($oUsuario);
        Sesion::volverIndexMensaje('Se ha modificado el nombre de usuario correctamente');

    } // if (!empty($_POST))

} catch (Exception $e){
    if (empty($aErrores)){
        Sesion::volverIndexMensaje($e);
    }

} // try
