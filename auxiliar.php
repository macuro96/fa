<?php
require_once "db/dbConfig.php";


function h($salida)
{
    return htmlspecialchars($salida, ENT_QUOTES | ENT_SUBSTITUTE);
}

function convertirParametroNull($parametro)
{
    return ($parametro != false ? $parametro : null);
} // function comprobarParametro($parametro)

/**
 * Busca una pelicula dentro de la base de datos a partir de su ID
 * @param  int     $id          ID de la pelicula
 * @return array   $aResultado  Devuelve la fila que ha encontrado, o false si no encuentra.
 */
function buscarPelicula(int $id)
{
    $aResultado = ['success' => false, 'error' => '', 'salida' => null];

    $db  = new PDO(DB_DSN, DB_USUARIO, DB_PASSWORD);
    $stm = $db->prepare('SELECT * FROM "peliculas" WHERE id = :id');
    $stm->bindValue(":id", $id);

    $bSelect = $stm->execute();
    $row     = $stm->fetchObject();

    if ($bSelect){
        $aResultado['success'] = $bSelect;
        $aResultado['salida']  = $row;

    } // if ($bSelect)

    return $aResultado;
}

/**
 * Borra una pelicula dentro de la base de datos a partir de su ID
 * @param  int    $id          ID de la pelicula
 * @return array  $aResultado  Devuelve la fila que ha encontrado, o false si no encuentra.
 */
function BorrarPelicula(int $id)
{
    $aResultado = ['success' => false, 'error' => '', 'salida' => null];

    $db  = new PDO(DB_DSN, DB_USUARIO, DB_PASSWORD);
    $stm = $db->prepare('DELETE FROM "peliculas" WHERE id = :id');
    $stm->bindValue(":id", $id);

    $bDelete = $stm->execute();
    $nRows   = $stm->rowCount();

    if ($bDelete){
        $aResultado['success'] = $bDelete;
        $aResultado['salida']  = $nRows;

    } // if ($bSelect)

    return $aResultado;
}

function filtrarPelicula($titulo, $bExacto = false)
{
    $aResultado = ['success' => false, 'error' => '', 'salida' => null];

    $db  = new PDO(DB_DSN, DB_USUARIO, DB_PASSWORD);

    if (!$bExacto){
        $stm = $db->prepare('SELECT * FROM "peliculas" WHERE "titulo" ILIKE :titulo');
        $stm->bindValue(":titulo", '%'.$titulo.'%');
    } else {
        $stm = $db->prepare('SELECT * FROM "peliculas" WHERE "titulo" = :titulo');
        $stm->bindValue(":titulo", $titulo);
    }

    $bSelect = $stm->execute();

    if ($bSelect){
        $aResultado['success'] = $bSelect;
        $aResultado['salida']  = $stm;

    } // if ($bSelect)

    return $aResultado;
}

function filtrarGenero($nombre, $bExacto = false){
    $aResultado = ['success' => false, 'error' => '', 'salida' => null];

    $db  = new PDO(DB_DSN, DB_USUARIO, DB_PASSWORD);

    $stm = $db->prepare('SELECT * FROM "generos" WHERE "nombre" ILIKE :nombre');
    $stm->bindValue(":nombre", (!$bExacto ? ('%'.$nombre.'%') : $nombre));


    $bSelect = $stm->execute();

    if ($bSelect){
        $aResultado['success'] = $bSelect;
        $aResultado['salida']  = $stm;

    } // if ($bSelect)

    return $aResultado;

} // function buscarGenero($nombre)
