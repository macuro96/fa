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

$iIdBorrar = input(INPUT_GET, 'id');
$sModo     = input(INPUT_GET, 'modo');

$oUsuario  = unserialize(Sesion::usuarioSesion());

try {
    if (empty($_GET)){
        throw new Exception('Hacen falta parámetros para poder realizar esa acción');
    }
    
    if ($sModo == MODO_PELICULAS){
        $oUsuario->borrarPelicula($iIdBorrar);
        $mensaje = 'La película se ha borrado correctamente';
    } else {
        $oUsuario->borrarGenero($iIdBorrar);
        $mensaje = 'El género se ha borrado correctamente';
    }

} catch (Exception $e){
    $mensaje = $e;
}

Sesion::volverIndexMensaje($mensaje);
