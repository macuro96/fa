<?php

/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

require_once 'F_Session.php'; SessionAuth();
require_once 'F_DB.php';

$accion      = trim(filter_input(INPUT_GET, 'accion'));
$idModificar = trim(filter_input(INPUT_GET, 'id'));    

$errores = array();

$titulo   = trim(filter_input(INPUT_POST, 'titulo'));
$anyo     = trim(filter_input(INPUT_POST, 'anyo'));
$sipnosis = trim(filter_input(INPUT_POST, 'sipnosis'));
$duracion = trim(filter_input(INPUT_POST, 'duracion'));
$genero   = trim(filter_input(INPUT_POST, 'genero'));

$sConsultaId = null;

try {
    if (($accion !== 'Modificar' && isset($idModificar)) && $accion !== 'Insertar'){
        throw new Exception('Acción no válida');
    }

    $stmGeneros = DBgeneros();

    if (!empty($_POST)){
        validar([
            'tituloPelicula'   => $titulo,
            'anyoPelicula'     => &$anyo,
            'duracionPelicula' => &$duracion,
            'generoPelicula'   => $genero
        ], $errores);

        if ($accion == 'Modificar'){
            DBmodificarPelicula($idModificar, $titulo, $anyo, $sipnosis, $genero, $duracion);
            SessionMensajeModificar("La película ${titulo} se ha modificado correctamente");

        } else if ($accion == 'Insertar'){ // if ($accion == 'Modificar')
            DBinsertarPelicula($titulo, $anyo, $sipnosis, $genero, $duracion);
            SessionMensajeModificar("La película ${titulo} se ha insertado correctamente");

        } // else if ($accion == 'Insertar')

        header('Location: index.php');
        exit;

    } else { // if (!empty($_POST))
        if ($accion == 'Modificar'){
            $rowPelicula = DBpeliculaId($idModificar);

            $titulo   = $rowPelicula->titulo;
            $anyo     = $rowPelicula->anyo;
            $sipnosis = $rowPelicula->sipnosis;
            $duracion = $rowPelicula->duracion;
            $genero   = $rowPelicula->genero_id;

        } // if ($accion == 'Modificar')

    } // else (!empty($_POST))

} catch (Exception $e){
    $mensaje = $e->getMessage();

    if ($mensaje != null){
        volverIndexMensaje($e);   
    } // if ($mensaje != null)

} // catch (Exception $e)

if ($accion == 'Modificar'){
    $sConsultaId = '&id='.$idModificar;
}

function gSelectGeneros($stmGeneros, $genero)
{
    ?>
    <select class="form-control" id="genero" name="genero">
        <?php
        while ($rowGenero = $stmGeneros->fetchObject()):
            $nombreGenero = $rowGenero->nombre;
            $idGenero     = $rowGenero->id;?>

            <option <?= ($idGenero === $genero ? 'selected' : '') ?> value="<?= h($idGenero) ?>"><?= h($nombreGenero) ?></option>

        <?php endwhile; ?>
    </select>
    <?php
}

function gAccionUrl($accion, $sConsultaId)
{
    ?>"accion-pelicula.php?accion=<?= h($accion) ?><?= isset($sConsultaId) ? h($sConsultaId) : '' ?>";<?php
}