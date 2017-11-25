<?php

require_once 'db/CFG_DB.php';

function DBconectar(){
    return new PDO(DB_DSN, DB_USUARIO, DB_PASSWORD);
}

function DBbuscarPeliculaId($id)
{
    $aResultado = array('success' => false, 'error' => '', 'salida' => null);

    $db = DBconectar();

    $stm = $db->prepare('SELECT * FROM "viewPeliculas" WHERE "id" = :id');
    $stm->bindValue(':id', $id);

    $bSelect = $stm->execute();

    if (!$bSelect){
        throw Exception('No se ha podido buscar la película correctamente');
    }

    $aResultado['success'] = true;
    $aResultado['salida']  = $stm;

    return $aResultado;

} // function DBbuscarPeliculaId($id)

function DBbuscarPeliculaTitulo($titulo, $bSipnosisCorta = false, $bExacto = false)
{
    $aResultado = array('success' => false, 'error' => '', 'salida' => null);

    $db = DBconectar();

    if (!$bExacto){
        $sql = ('SELECT ' . ($bSipnosisCorta ? '"titulo", "anyo", left("sipnosis", 40) AS "sipnosis", "duracion", "genero"' :  '*') . 
                '  FROM  "viewPeliculas" WHERE "titulo" ILIKE :titulo');

        $stm = $db->prepare($sql);
        $stm->bindValue(':titulo', '%'.$titulo.'%');

    } else { // if (!$bExacto)
        $sql = ('SELECT ' . ($bSipnosisCorta ? '"titulo", "anyo", left("sipnosis", 40) AS "sipnosis", "duracion", "genero"' :  '*') . 
                '  FROM  "viewPeliculas" WHERE "titulo" = :titulo');

        $stm = $db->prepare($sql);
        $stm->bindValue(':titulo', $titulo);

    } // else (!$bExacto)

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

    $db = DBconectar();

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

    $db = DBconectar();

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

// Validaciones

function validar($aCampos)
{
    $errores = array();

    foreach ($aCampos as $nombre => $valor){
        switch ($nombre){
            case 'tituloPelicula':
                if (!isset($valor)){
                    $errores[]  = 'El título es obligatorio';
                } else if (mb_strlen($valor) > 255) {
                    $errores[] = 'El título es demasiado largo';
                }
                break;

            case 'anyoPelicula':
                if (filter_var($valor, FILTER_VALIDATE_INT, [
                    'options' => [
                        'min_range' => 0,
                        'max_range' => 9999
                    ]
                ]) === false){ $errores[] = 'El año no es válido'; }
                break;

            case 'duracionPelicula':    // Duracion en minutos
                if (filter_var($valor, FILTER_VALIDATE_INT, [
                    'options' => [
                        'min_range' => 0,
                        'max_range' => 9999
                    ]
                ]) === false){ $errores[] = 'El año no es válido'; }
                break;

            case 'generoPelicula':
                if (!isset($valor)){
                    $errores[]  = 'El genero es obligatorio';
                } else if (mb_strlen($valor) > 255) {
                    $errores[] = 'El genero es demasiado largo';
                }
                break;            

            default: break;

        } // switch ($nombre)

    } // foreach ($aCampos as $nombre => $valor)

    if (!empty($errores)){
        throw new Exception($errores);
    }

} // function validar($aCampos)