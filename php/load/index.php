<?php
/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

namespace php\clases;

require_once 'php/autoload.php';
use \Exception;

$sCadenaBuscador = input(INPUT_GET, 'cadena-buscador');
$sModo           = input(INPUT_GET, 'modo')       ?? MODO_PELICULAS;
$sFiltro         = input(INPUT_GET, 'filtro')     ?? 'titulo';
$sOrden          = input(INPUT_GET, 'orden')      ?? ($sModo == MODO_PELICULAS ? 'titulo' : 'nombre');
$sDireccion      = input(INPUT_GET, 'dir')        ?? DIRECCION_ASCENDENTE;
$iPaginaActual   = input(INPUT_GET, 'pag-actual') ?? 1;

$sNombreUsuario = '';

if (Sesion::existeSesionUsuario()){
    $oUsuario = unserialize(Sesion::usuarioSesion());
    $sNombreUsuario = $oUsuario->getNombre();
}

if (isset($_GET['dir'])){
    $sDireccionCambiar = ($sDireccion == DIRECCION_ASCENDENTE ? DIRECCION_DESCENDENTE : DIRECCION_ASCENDENTE);
} else {
    $sDireccionCambiar = $sDireccion;
}

$sModoEnlace = ($sModo == MODO_PELICULAS ? MODO_GENEROS : MODO_PELICULAS);

const PARAMETROS_CAMBIAR_VALIDOS = ['orden', 'dir'];
const PARAMETROS_ORDEN_VALIDOS = ['titulo', 'anyo', 'sipnosis', 'duracion', 'genero', 'nombre'];
try {
    if ($sModo == MODO_PELICULAS){
        $iMaxElementosPorPagina = Pelicula::LIMITE_BUSQUEDA;
        $iOffset                = ($iPaginaActual - 1) * Pelicula::LIMITE_BUSQUEDA;

        $aPeliculas         = Pelicula::buscador($sCadenaBuscador, $sFiltro, $sOrden, $sDireccion, $iOffset);
        $nPeliculasBusqueda = Pelicula::totalPeliculas($sCadenaBuscador, $sFiltro);

        $iTotalElementos = $nPeliculasBusqueda;

        for ($p = 0; $p < count($aPeliculas); $p++){
            $aGenero = ['id' => $aPeliculas[$p]['genero_id'], 'nombre' => $aPeliculas[$p]['genero']];
            $aPeliculas[$p]['genero'] = $aGenero;
        }

    } else if ($sModo == MODO_GENEROS) { // if ($sModo == MODO_PELICULAS)
        $iMaxElementosPorPagina = Genero::LIMITE_BUSQUEDA;
        $iOffset                = ($iPaginaActual - 1) * Genero::LIMITE_BUSQUEDA;

        $aGeneros         = Genero::buscadorNombre($sCadenaBuscador, $sOrden, $sDireccion, $iOffset);
        $nGenerosBusqueda = Genero::totalGeneros($sCadenaBuscador);

        $iTotalElementos = $nGenerosBusqueda;

    } // else ($sModo == MODO_PELICULAS)

} catch (Exception $e){
    Sesion::modificarMensaje($e->getMessage());
}

function enlaceOrden($sNombre, $sOrden, $sDireccion)
{
    if (isset($_GET['orden'])){
        if (in_array($_GET['orden'], PARAMETROS_ORDEN_VALIDOS)){
            if ($_GET['orden'] != $sOrden && $sDireccion != DIRECCION_ASCENDENTE){
                $sDireccion = DIRECCION_ASCENDENTE;
            }

        } // if (in_array($_GET['orden'], PARAMETROS_ORDEN_VALIDOS))

    } // if (isset($_GET['orden']))

    ?><a href="<?= cambiarParamEnlaceActual(PARAMETROS_CAMBIAR_VALIDOS, ['orden', 'dir'], [$sOrden, $sDireccion]) ?>"><?= h($sNombre) ?></a><?php

} // function enlaceOrden($sNombre, $sOrden, $sDireccion)
