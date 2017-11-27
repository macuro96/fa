<?php

/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

require_once 'db/CFG_DB.php';

function DBconectar(){
    return new PDO(DB_DSN, DB_USUARIO, DB_PASSWORD);
}

function DBbuscarPeliculaId($id, $bVista = true)
{
    $aResultado = array('success' => false, 'error' => '', 'salida' => null);

    $db = DBconectar();

    $stm = $db->prepare('SELECT * FROM '.($bVista ? "viewPeliculas" : "peliculas").' WHERE "id" = :id');
    $stm->bindValue(':id', $id);

    $bSelect = $stm->execute();

    if (!$bSelect){
        throw new Exception('No se ha podido buscar la película correctamente');
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
        throw new Exception('No se ha podido buscar la película correctamente');
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
        throw new Exception('No se ha podido buscar el género correctamente');
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
        throw new Exception('No se ha podido buscar el género correctamente');
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
        throw new Exception('No se ha podido comprobar la película correctamente');
    }

    $aResultado['success'] = true;
    $aResultado['salida']  = $stm->fetchColumn() > 0;

    return $aResultado;

} // function DBexisteGeneroNombre($nombre)

function DBexisteGeneroId($id)
{
    $aResultado = array('success' => false, 'error' => '', 'salida' => null);

    $db = DBconectar();

    $stm = $db->prepare('SELECT COUNT(*) FROM "generos" WHERE "id" = :id');
    $stm->bindValue(':id', $id);

    $bSelect = $stm->execute();

    if (!$bSelect){
        throw new Exception('No se ha podido comprobar el género correctamente');
    }

    $aResultado['success'] = true;
    $aResultado['salida']  = $stm->fetchColumn() > 0;

    return $aResultado;

} // function DBexistePeliculaId($id)

function DBexistePeliculaId($id)
{
    $aResultado = array('success' => false, 'error' => '', 'salida' => null);

    $db = DBconectar();

    $stm = $db->prepare('SELECT COUNT(*) FROM "peliculas" WHERE "id" = :id');
    $stm->bindValue(':id', $id);

    $bSelect = $stm->execute();

    if (!$bSelect){
        throw new Exception('No se ha podido comprobar la película correctamente');
    }

    $aResultado['success'] = true;
    $aResultado['salida']  = $stm->fetchColumn() > 0;

    return $aResultado;

} // function DBexistePeliculaId($id)

function DBmodificarPelicula($id, $titulo, $anyo, $sipnosis, $genero_id, $duracion = 'default')
{
    $aResultado = array('success' => false, 'error' => '', 'salida' => null);

    $db = DBconectar();
    
    $stm = $db->prepare('UPDATE "peliculas" SET "titulo"    = :titulo,       "sipnosis"  = :sipnosis,
                                                "anyo"      = :anyo,         "genero_id" = :genero_id'.
                                                ($duracion != 'default' ? ', "duracion"  = :duracion' : '').                                                
                        ' WHERE "id" = :id');

    $stm->bindValue(':id', $id);
    $stm->bindValue(':titulo', $titulo);
    $stm->bindValue(':sipnosis', $sipnosis);
    $stm->bindValue(':anyo', $anyo);
    $stm->bindValue(':genero_id', $genero_id);
    if ($duracion != 'default'){ $stm->bindValue('duracion', $duracion); }

    $bUpdate = $stm->execute();

    if (!$bUpdate){
        throw new Exception('No se ha podido modificar la película correctamente');
    }

    $aResultado['success'] = true;
    $aResultado['salida']  = ($stm->rowCount() > 0);

    return $aResultado;

} // function DBmodificarPelicula($id, $titulo, $anyo, $sipnosis, $genero_id, $duracion = 'default')

function DBinsertarPelicula($titulo, $anyo, $sipnosis, $genero_id, $duracion = 'default')
{
    $aResultado = array('success' => false, 'error' => '', 'salida' => null);

    $db = DBconectar();

    $stm = $db->prepare('INSERT INTO "peliculas" (titulo, sipnosis, anyo, genero_id'.($duracion != 'default' ? ', duracion' : '').')
                              VALUES (:titulo, :sipnosis, :anyo, :genero_id'.($duracion != 'default' ? ', :duracion' : '').')');

    $stm->bindValue(':titulo', $titulo);
    $stm->bindValue(':sipnosis', $sipnosis);
    $stm->bindValue(':anyo', $anyo);
    $stm->bindValue(':genero_id', $genero_id);
    if ($duracion != 'default'){ $stm->bindValue('duracion', $duracion); }

    $bInsert = $stm->execute();

    if (!$bInsert){
        throw new Exception('No se ha podido insertar la película correctamente');
    }

    $aResultado['success'] = true;
    $aResultado['salida']  = ($stm->rowCount() > 0);

    return $aResultado;

} // function DBinsertarPelicula($id, $titulo, $anyo, $sipnosis, $genero_id, $duracion = null)

function comprobarPorDefecto($valor)
{
    return ($valor == 'default' ? null : $valor);
}

function validar($aCampos, &$errores)
{
    foreach ($aCampos as $nombre => &$valor){
        switch ($nombre){           
            case 'tituloPelicula':
                if ($valor == null){
                    $errores[] = 'El título es obligatorio';
                } else if (mb_strlen($valor) > 255) {
                    $errores[] = 'El título es demasiado largo';
                }
                break;

            case 'anyoPelicula':
                if ($valor == null && !is_null($valor)){
                    $valor = null;
                } else { // if ($valor == null && !is_null($valor))
                    if (filter_var($valor, FILTER_VALIDATE_INT, [
                        'options' => [
                            'min_range' => 0,
                            'max_range' => 9999
                        ]
                    ]) === false){ $errores[] = 'El año no es válido'; }

                } // else ($valor == null && !is_null($valor))
                break;

            case 'duracionPelicula':    // Duracion en minutos
                if ($valor == null){                        
                    $valor = 'default';
                } else { // if ($valor == null)
                    if (filter_var($valor, FILTER_VALIDATE_INT, [
                        'options' => [
                            'min_range' => 0,
                            'max_range' => 9999
                        ]
                    ]) === false){ $errores[] = 'La duración no es válida'; }

                } // else ($valor == null)
                break;

            case 'generoPelicula':
                if ($valor == null){
                    $errores[]  = 'El genero es obligatorio';
                } else {
                    try {
                        $aResultadoSQLGeneroAccion = DBexisteGeneroId($valor);
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