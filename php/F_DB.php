<?php

/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

require_once 'db/CFG_DB.php';

define('TABLA_PELICULAS', '"peliculas"');
define('TABLA_GENEROS', '"generos"');
define('TABLA_USUARIOS', '"usuarios"');

define('VISTA_PELICULAS', '"viewPeliculas"');

function DBconectar(){
    return new PDO(DB_DSN, DB_USUARIO, DB_PASSWORD);
}

function DBsql($sql, $error, $aCampos = []){
    $db = DBconectar();

    $stm = $db->prepare($sql);

    foreach ($aCampos as $campo => $valor){
        $stm->bindValue($campo, $valor);                
    } // foreach ($aCampos as $campo => $valor)

    $bSQL = $stm->execute();

    if (!$bSQL){
        throw new Exception($error);
    }

    return $stm;

} // function DBselect($aCampos, $sFrom, $aWhere)

function DBbuscarPeliculaId($id, $bVista = true)
{
    $aResultado = array('success' => false, 'error' => '', 'salida' => null);

    $db = DBconectar();

    $stm = $db->prepare('SELECT * FROM '.($bVista ? '"viewPeliculas"' : '"peliculas"').' WHERE "id" = :id');
    $stm->bindValue(':id', $id);

    $bSelect = $stm->execute();

    if (!$bSelect){
        throw new Exception('No se ha podido encontrar la película correctamente');
    }

    $aResultado['success'] = true;
    $aResultado['salida']  = $stm;

    return $aResultado;

} // function DBbuscarPeliculaId($id)

function DBbuscarPeliculaTitulo($titulo, $bSipnosisCorta = false, $bExacto = false)
{
    $error = 'No se ha podido buscar la película correctamente';

    if (!$bExacto){
        $sql = ('SELECT ' . ($bSipnosisCorta ? '"id", "titulo", "anyo", left("sipnosis", 40) AS "sipnosis", "duracion", "genero"' :  '*') . 
                '  FROM  '.VISTA_PELICULAS.' WHERE "titulo" ILIKE :titulo');
        $aCampos = [':titulo' => '%'.$titulo.'%'];

    } else {
        $sql = ('SELECT ' . ($bSipnosisCorta ? '"id", "titulo", "anyo", left("sipnosis", 40) AS "sipnosis", "duracion", "genero"' :  '*') . 
                '  FROM  "viewPeliculas" WHERE "titulo" = :titulo');        
        $aCampos = [':titulo' => $titulo];
    }

    $stm = DBsql($sql, $error, $aCampos);

    return $stm;

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

function DBborrarPelicula($id)
{
    $aResultado = array('success' => false, 'error' => '', 'salida' => null);

    $db = DBconectar();

    $bExistePeliculaId = DBexistePeliculaId($id)['salida'];

    if (!$bExistePeliculaId){
        throw new Exception('No existe una película con ese identificador');
    }

    $stm = $db->prepare('DELETE FROM "peliculas" WHERE "id" = :id');
    $stm->bindValue(':id', $id);

    $bDelete = $stm->execute();

    if (!$bDelete){
        throw new Exception('No se ha podido borrar la película correctamente');
    }

    $aResultado['success'] = true;
    $aResultado['salida']  = ($stm->rowCount() > 0);

    return $aResultado;    

} // function DBborrarPelicula($id)

function DBbuscarUsuario($nombre, $password)
{
    $aResultado = array('success' => false, 'error' => '', 'salida' => null);

    $db = DBconectar();

    $stm = $db->prepare('SELECT * FROM "usuarios" WHERE "nombre" = :nombre');
    $stm->bindValue(':nombre', $nombre);

    $bSelect = $stm->execute();

    if (!$bSelect){
        throw new Exception('No se ha podido realizar la búsqueda del usuario');
    }

    $aResultado['success'] = true;
    $aResultado['salida']  = $stm->fetchObject();

    return $aResultado;

} // function DBbuscarUsuario($nombre, $password)

function comprobarPorDefecto($valor)
{
    return ($valor == 'default' ? null : $valor);

} // function comprobarPorDefecto($valor)

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

            case 'nombreUsuario':
                if ($valor == null){
                    $errores[] = 'El usuario es obligatorio';
                } else { // if ($valor == null){
                    if (mb_strlen($valor) > 255){
                        $errores[] = 'El nombre es demasiado largo';
                    }                    
                    if (mb_strpos($valor, ' ') !== false){
                        $errores[] = 'El nombre no puede contener espacios';
                    }

                } // else ($valor == null){
                break;

            case 'passwordUsuario':
                    if ($valor == null){
                        $errores[] = 'La contraseña es obligatoria';
                    } // if ($valor == null)
                break;

        } // switch ($nombre)

    } // foreach ($aCampos as $nombre => $valor)

    if (!empty($errores)){
        throw new Exception();
    }

} // function validar($aCampos)