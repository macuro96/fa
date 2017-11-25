<?php

require_once 'db/CFG_DB.php';

$db = new PDO(DB_DSN, DB_USUARIO, DB_PASSWORD);

function DBbuscarPeliculaId($id)
{
    $aResultado = array('success' => false, 'error' => '', 'salida' => null);

    $stm = $db->prepare('SELECT * FROM "peliculas" WHERE "id" = :id');
    $stm->bindValue(':id', $id);

    $bSelect = $stm->execute();

    if (!$bSelect){
        throw Exception('No se ha podido buscar la película correctamente');
    }

    $aResultado['success'] = true;
    $aResultado['salida']  = $stm;

    return $aResultado;

} // function DBbuscarPeliculaId($id)

function DBbuscarPeliculaTitulo($titulo, $bExacto = false)
{
    $aResultado = array('success' => false, 'error' => '', 'salida' => null);

    $stm = ($bExacto ? $db->prepare('SELECT * FROM "peliculas" WHERE "titulo" = :titulo')
                     : $db->prepare('SELECT * FROM "peliculas" WHERE "titulo" ILIKE :titulo'));
    $stm->bindValue(':titulo', (!$bExacto ? '%' : '').$titulo.(!$bExacto ? '%' : ''));

    $bSelect = $stm->execute();

    if (!$bSelect){
        throw Exception('No se ha podido buscar la película correctamente');
    }

    $aResultado['success'] = true;
    $aResultado['salida']  = $stm;

    return $aResultado;

} // function DBbuscarPeliculaTitulo($titulo, $bExacto = true)

function DBbuscarGeneroId($id)
{
    $aResultado = array('success' => false, 'error' => '', 'salida' => null);

    $stm = $db->prepare('SELECT * FROM "generos" WHERE "id" = :id');
    $stm->bindValue(':id', $id);

    $bSelect = $stm->execute();

    if (!$bSelect){
        throw Exception('No se ha podido buscar el género correctamente');
    }

    $aResultado['success'] = true;
    $aResultado['salida']  = $stm;

    return $aResultado;

} // function DBbuscarGeneroId($id)

function DBbuscarGeneroNombre($nombre, $bExacto = false)
{
    $aResultado = array('success' => false, 'error' => '', 'salida' => null);

    $stm = ($bExacto ? $db->prepare('SELECT * FROM "generos" WHERE "nombre" = :nombre')
                     : $db->prepare('SELECT * FROM "generos" WHERE "nombre" ILIKE :nombre'));
    $stm->bindValue(':nombre', (!$bExacto ? '%' : '').$nombre.(!$bExacto ? '%' : ''));

    $bSelect = $stm->execute();

    if (!$bSelect){
        throw Exception('No se ha podido buscar el género correctamente');
    }

    $aResultado['success'] = true;
    $aResultado['salida']  = $stm;

    return $aResultado;

} // function DBbuscarGeneroNombre($nombre, $bExacto = false)