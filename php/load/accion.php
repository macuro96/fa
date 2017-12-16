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

$sModo        = input(INPUT_GET, 'modo');
$accion       = input(INPUT_GET, 'accion');
$iIdModificar = input(INPUT_GET, 'id');

$sTitulo    = input(INPUT_POST, 'titulo');
$iAnyo      = input(INPUT_POST, 'anyo');
$sSipnosis  = input(INPUT_POST, 'sipnosis');
$iDuracion  = input(INPUT_POST, 'duracion');
$iGenero_id = input(INPUT_POST, 'genero_id');

$sNombreGenero = input(INPUT_POST, 'nombre-genero');

$aErrores = [];
$oUsuario = unserialize(Sesion::usuarioSesion());

const ACCION_MODIFICAR = 'Modificar';
const ACCION_INSERTAR  = 'Insertar';

if ($accion == ACCION_MODIFICAR){
    $sConsultaId = '&id='.$iIdModificar;
}

try {
    if (empty($_GET)){
        throw new Exception('Hacen falta parámetros para poder realizar esa acción');
    }
    
    if (($accion != ACCION_MODIFICAR && isset($iIdModificar)) && $accion != ACCION_INSERTAR){
        throw new Exception('Acción no válida');
    }

    $aGenerosAll = Genero::datosMixed();

    if (!empty($_POST)){
        if ($sModo == MODO_PELICULAS){
            $aErroresP = Pelicula::validar(['titulo'    => $sTitulo,
                                            'anyo'      => $iAnyo,
                                            'duracion'  => $iDuracion]);

            $aErroresG = Genero::validar(['id' => $iGenero_id]);
            $aErrores  = array_merge($aErroresP, $aErroresG);

        } else { // if ($sModo = MODO_PELICULAS)
            $aErrores = Genero::validar(['nombre' => $sNombreGenero]);
        } // else ($sModo = MODO_PELICULAS)

        exceptionErrores($aErrores);

        if ($accion == ACCION_MODIFICAR){
            if ($sModo == MODO_PELICULAS){
                $oUsuario->modificarPelicula($iIdModificar, $sTitulo, $iAnyo, $sSipnosis, $iGenero_id, $iDuracion);
                Sesion::volverIndexMensaje("Se ha modificado la película con exito");

            } else {  // if ($sModo == MODO_PELICULAS)
                $oUsuario->modificarGenero($iIdModificar, $sNombreGenero);
                Sesion::volverIndexMensaje("Se ha modificado el género con exito");

            } // else ($sModo == MODO_PELICULAS)

        } else {
            if ($sModo == MODO_PELICULAS){
                $oUsuario->insertarPelicula($sTitulo, $iAnyo, $sSipnosis, $iGenero_id, $iDuracion);
                Sesion::volverIndexMensaje("Se ha insertado la película con exito");

            } else { // if ($sModo == MODO_PELICULAS)
                $oUsuario->insertarGenero($sNombreGenero);
                Sesion::volverIndexMensaje("Se ha insertado el género con exito");

            } // else ($sModo == MODO_PELICULAS)

        } // if ($accion == ACCION_MODIFICAR)

    } else if ($accion == ACCION_MODIFICAR){ // if (!empty($_POST))
        if ($sModo == MODO_PELICULAS){
            $aPelicula = Pelicula::datosId($iIdModificar);
            $aGenero   = ['id' => $aPelicula['genero_id'], 'nombre' => $aPelicula['genero']];

            $sTitulo    = $aPelicula['titulo'];
            $iAnyo      = $aPelicula['anyo'];
            $sSipnosis  = $aPelicula['sipnosis'];
            $iDuracion  = $aPelicula['duracion'];
            $iGenero_id = $aGenero['id'];

        } else { // if ($sModo == MODO_PELICULAS)
            $aGenero       = Genero::datosMixed([':id' => $iIdModificar]);
            $sNombreGenero = $aGenero['nombre'];

        } // else ($sModo == MODO_PELICULAS)

    } // else if ($accion == ACCION_MODIFICAR)

} catch (Exception $e) {
    if (empty($aErrores)){
        Sesion::volverIndexMensaje($e);
    }

} // try
