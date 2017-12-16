<?php
/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

require_once 'php/autoload.php';

use \php\clases\Sesion;

const MODO_PELICULAS = 'peliculas';
const MODO_GENEROS   = 'generos';

const DIRECCION_ASCENDENTE   = 'asc';
const DIRECCION_DESCENDENTE = 'desc';

function h($cadena)
{
    return htmlspecialchars($cadena, ENT_QUOTES | ENT_SUBSTITUTE);
}

function exceptionErrores(array $aErrores)
{
    if (!empty($aErrores)){ throw new Exception(); }
}

function mostrarErrores($aErrores)
{
    if (!empty($aErrores)){
        foreach ($aErrores as $sError):?>
            <h3><?= $sError ?></h3>
        <?php endforeach;
    }

} // public static function mostrarErrores()

function input($iFiltro, $sInput)
{
    $sFilter    = trim(filter_input($iFiltro, $sInput));
    $sRespuesta = ($sFilter == null ? null : $sFilter);

    return $sRespuesta;

} // function input($iFiltro, $sInput)

function encabezado($sTitulo)
{ ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $sTitulo ?></title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

<?php } // function encabezado($sTitulo)

function logeo($sNombreUsuario)
{
    if (Sesion::existeSesionUsuario()): ?>
        <div class="col-lg-offset-3 col-lg-1">
            <h4><?= h($sNombreUsuario); ?></h4>
        </div>
        <div class="col-lg-2">
            <a class="btn btn-primary center-block" href="logout.php" role="button">Cerrar sesión</a>
        </div>
        <div class="col-lg-2">
            <a class="btn btn-primary center-block" href="cambiar-password.php" role="button">Cambiar contraseña</a>
        </div>
        <div class="col-lg-2">
            <a class="btn btn-primary center-block" href="cambiar-nombre.php" role="button">Cambiar nombre</a>
        </div>
    <?php else: ?>
        <div class="col-lg-offset-5 col-lg-2">
            <a class="btn btn-primary center-block" href="login.php" role="button">Iniciar sesión</a>
        </div>
    <?php endif;
}

function generarOption($sNombre, $sValue, $sSelected)
{
    ?><option<?= ($sValue == $sSelected ? ' selected' : '') ?> value="<?= h($sValue) ?>"><?= h($sNombre) ?></option><?php
}

function cambiarParamEnlaceActual($aParametrosValidos, $aNombresParam, $aValoresParam)
{
    $numParamCorrectos = 0;
    $aParametros       = [];

    for ($i = 0; $i < count($aNombresParam); $i++){
        if (in_array($aNombresParam[$i], $aParametrosValidos)){
            $numParamCorrectos++;
        } // if (in_array($aNombresParam, $aParametrosValidos))

    } // for ($i = 0; $i < count($aNombresParam); $i++)

    if ($numParamCorrectos == count($aParametrosValidos)){
        $aParametros = $_GET;

        for ($i = 0; $i < count($aNombresParam); $i++){
            $aParametros[$aNombresParam[$i]] = $aValoresParam[$i];
        }

    } // if ($numParamCorrectos == count($aParametrosValidos))

    ?><?= enlaceHostActual() ?>?<?= http_build_query($aParametros) ?><?php

} // function cambiarParamEnlaceActual($aParametrosValidos, $aNombresParam, $aValoresParam)

function enlaceActual()
{
    ?><?= enlaceHostActual() ?><?= $_SERVER['REQUEST_URI']?><?php
}

function enlaceHostActual()
{
    ?>http://<?= $_SERVER['HTTP_HOST'] ?><?php
}

function paginador($iPaginaActual, $iMaxElementosPorPagina, $iTotalElementos)
{
    $iMaxPaginas      = ceil($iTotalElementos / $iMaxElementosPorPagina);
    $iPaginaPrevia    = ($iPaginaActual == 1 ? 1 : $iPaginaActual - 1);
    $iPaginaSiguiente = ($iPaginaActual == $iMaxPaginas ? $iMaxPaginas : $iPaginaActual + 1);

    ?>
    <nav aria-label="Page navigation">
      <ul class="pagination">
        <li <?= ($iPaginaActual <= 1 ? 'class="disabled"' : '') ?> >
            <a href="<?= cambiarParamEnlaceActual(['pag-actual'], ['pag-actual'], [$iPaginaPrevia]) ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?php
        for ($p = 1; $p <= $iMaxPaginas; $p++):
            ?><li <?= ($iPaginaActual == $p ? 'class="active"' : '') ?>><a href="<?= cambiarParamEnlaceActual(['pag-actual'], ['pag-actual'], [$p]) ?>"><?= $p ?></a></li><?php
        endfor;
        ?>
        <li <?= ($iPaginaActual >= $iMaxPaginas ? 'class="disabled"' : '') ?>>
          <a href="<?= cambiarParamEnlaceActual(['pag-actual'], ['pag-actual'], [$iPaginaSiguiente]) ?>" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </nav>
    <?php

} // function paginador($iPaginaActual, $iMaxPaginas, $iMaxElementos)
