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
        $sql = ('SELECT ' . ($bSipnosisCorta ? '"id", "titulo", "anyo", left("sipnosis", 40) AS "sipnosis", "duracion", "genero"' :  '*') . 
                '  FROM  "viewPeliculas" WHERE "titulo" ILIKE :titulo');

        $stm = $db->prepare($sql);
        $stm->bindValue(':titulo', '%'.$titulo.'%');

    } else { // if (!$bExacto)
        $sql = ('SELECT ' . ($bSipnosisCorta ? '"id", "titulo", "anyo", left("sipnosis", 40) AS "sipnosis", "duracion", "genero"' :  '*') . 
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

function DBexisteGeneroNombre($nombre)
{
    $aResultado = array('success' => false, 'error' => '', 'salida' => null);

    $db = DBconectar();

    $stm = $db->prepare('SELECT COUNT(*) FROM "generos" WHERE "nombre" = :nombre');
    $stm->bindValue(':nombre', $nombre);

    $bSelect = $stm->execute();

    if (!$bSelect){
        throw Exception('No se ha podido comprobar la película correctamente');
    }

    $aResultado['success'] = true;
    $aResultado['salida']  = $stm->fetchColumn() > 0;

    return $aResultado;

} // function DBexisteGeneroNombre($nombre)

function DBexistePeliculaId($id)
{
    $aResultado = array('success' => false, 'error' => '', 'salida' => null);

    $db = DBconectar();

    $stm = $db->prepare('SELECT COUNT(*) FROM "peliculas" WHERE "id" = :id');
    $stm->bindValue(':id', $id);

    $bSelect = $stm->execute();

    if (!$bSelect){
        throw Exception('No se ha podido comprobar la película correctamente');
    }

    $aResultado['success'] = true;
    $aResultado['salida']  = $stm->fetchColumn() > 0;

    return $aResultado;

} // function DBexistePeliculaId($id)

function validar($aCampos, &$errores)
{
    foreach ($aCampos as $nombre => $valor){
        switch ($nombre){           
            case 'tituloPelicula':
                if ($valor == null){
                    $errores[] = 'El título es obligatorio';
                } else if (mb_strlen($valor) > 255) {
                    $errores[] = 'El título es demasiado largo';
                }
                break;

            case 'anyoPelicula':
                if ($valor != null && filter_var($valor, FILTER_VALIDATE_INT, [
                    'options' => [
                        'min_range' => 0,
                        'max_range' => 9999
                    ]
                ]) === false){ $errores[] = 'El año no es válido'; }
                break;

            case 'duracionPelicula':    // Duracion en minutos
                if ($valor != null && filter_var($valor, FILTER_VALIDATE_INT, [
                    'options' => [
                        'min_range' => 0,
                        'max_range' => 9999
                    ]
                ]) === false){ $errores[] = 'La duración no es válida'; }
                break;

            case 'generoPelicula':
                if ($valor == null){
                    $errores[]  = 'El genero es obligatorio';
                } else if (mb_strlen($valor) > 255) {
                    $errores[] = 'El genero es demasiado largo';
                } else {
                    try {
                        $aResultadoSQLGeneroAccion = DBexisteGeneroNombre($valor);
                        $bExisteGenero             = $aResultadoSQLGeneroAccion['salida'];

                        if (!$bExisteGenero){
                            $errores[] = 'El género no es válido';
                        } // if (!$bExisteGenero)

                    } catch (Exception $e){
                        $errores[] = $e->getMessage();
                    } // catch (Exception $e)

                } // else

                break;            

            default: break;

        } // switch ($nombre)

    } // foreach ($aCampos as $nombre => $valor)

    if (!empty($errores)){
        throw new Exception();
    }

} // function validar($aCampos)