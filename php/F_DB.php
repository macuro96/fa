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

function DBbuscadorPelicula($titulo)
{
    return DBsql('SELECT "id", "titulo", "anyo", left("sipnosis", 40) AS "sipnosis", "duracion", "genero"
                    FROM '.VISTA_PELICULAS.' WHERE "titulo" ILIKE :titulo',

                 'No se ha podido buscar la película con ese título correctamente',                 
                 ['titulo' => '%'.$titulo.'%']);

} // function DBbuscadorPelicula($titulo)

function DBpeliculaId($id)
{
    $stm = DBsql('SELECT * FROM '.VISTA_PELICULAS.' WHERE "id" = :id',                 

                 'No se ha podido encontrar la película con ese identificador correctamente',                 
                 [':id' => $id]);

    $row = DBcomprobarStm($stm, 'No existe una película con ese identificador');

    return $row;

} // function DBbuscarPeliculaId($id)

function DBgeneroId($id)
{
    $stm = DBsql('SELECT * FROM '.TABLA_GENEROS.' WHERE "id" = :id',                 

                 'No se ha podido encontrar un género con ese identificador correctamente',                 
                 [':id' => $id]);    

    $row = DBcomprobarStm($stm, 'No existe un género con ese identificador');

    return $row;

} // function DBgeneroId($id)

function DBgeneroNombre($nombre)
{
    $stm =  DBsql('SELECT * FROM '.TABLA_GENEROS.' WHERE "nombre" = :nombre',
                  'No se ha podido encontrar un género con ese nombre correctamente',
                  [':nombre' => $nombre]);

    $row = DBcomprobarStm($stm, 'No existe un género con ese nombre');

    return $row;

} // function DBgeneroNombre($nombre)

function DBgeneros()
{
    return DBsql('SELECT * FROM '.TABLA_GENEROS.'',
                 'No se han podido encontrar todos los géneros correctamente');

} // function DBgeneros()

function DBmodificarPelicula($id, $titulo, $anyo, $sipnosis, $genero_id, $duracion = 'default')
{
    $aCampos = [
          ':id'       => $id
        , ':titulo'   => $titulo
        , ':sipnosis' => $sipnosis
        , ':anyo'     => $anyo
        , 'genero_id' => $genero_id
    ];
    
    if ($duracion != 'default'){
        $aCampos[':duracion'] = $duracion; 
    }

    $stm = DBsql('UPDATE '.TABLA_PELICULAS.'
                     SET "titulo" = :titulo, "sipnosis" = :sipnosis, "anyo" = :anyo, "genero_id" = :genero_id'.
                         ($duracion != 'default' ? ', "duracion"  = :duracion' : '').                                                
                        ' WHERE "id" = :id',

                 'No se ha podido modificar la película correctamente',
                 $aCampos);

    $nFilasAfectadas = $stm->rowCount();

    return ($nFilasAfectadas > 0);

} // function DBmodificarPelicula($id, $titulo, $anyo, $sipnosis, $genero_id, $duracion = 'default')

function DBinsertarPelicula($titulo, $anyo, $sipnosis, $genero_id, $duracion = 'default')
{
    $aCampos = [
        ':titulo'    => $titulo
      , ':sipnosis'  => $sipnosis
      , ':anyo'      => $anyo
      , ':genero_id' => $genero_id
    ];

    if ($duracion != 'default'){
        $aCampos[':duracion'] = $duracion; 
    }

    $stm = DBsql('INSERT INTO '.TABLA_PELICULAS.' (titulo, sipnosis, anyo, genero_id'.($duracion != 'default' ? ', duracion'  : '').')
                               VALUES (:titulo, :sipnosis, :anyo, :genero_id'.($duracion != 'default' ? ', :duracion' : '').')',

                'No se ha podido insertar la película correctamente',
                $aCampos);

    $nFilasAfectadas = $stm->rowCount();

    return ($nFilasAfectadas > 0);

} // function DBinsertarPelicula($titulo, $anyo, $sipnosis, $genero_id, $duracion = 'default')

function DBborrarPelicula($id)
{
    DBpeliculaId($id);

    $stm = DBsql('DELETE FROM '.TABLA_PELICULAS.' WHERE "id" = :id',
                 
                 'No se ha podido borrar la pelicula con ese identificador correctamente',
                 [':id' => $id]);

    $nFilasAfectadas = $stm->rowCount();

    return ($nFilasAfectadas > 0);

} // function DBborrarPelicula($id)

function DBusuario($nombre, $password)
{
    $error = 'No existe ningún usuario con ese nombre y/o contraseña';

    $stm = DBsql('SELECT * FROM '.TABLA_USUARIOS.' WHERE "nombre" = :nombre',
                 
                 'No se ha podido realizar la búsqueda del usuario',
                 [':nombre' => $nombre]);

    $row = DBcomprobarStm($stm, $error);

    $bPasswordCorrecto = password_verify($password, $row->password);

    if (!$bPasswordCorrecto){
        throw new Exception($error);
    }

    return $row;

} // function DBusuario($nombre, $password)

function DBconectar(){
    return new PDO(DB_DSN, DB_USUARIO, DB_PASSWORD);

} // function DBconectar()

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

function DBcomprobarStm($stm, $error)
{
    $row = $stm->fetchObject();

    if (!$row){
        throw new Exception($error);
    }

    return $row;

} // function DBcomprobarStm($stm)

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
                        $rowGeneroId = DBgeneroId($valor);
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

function comprobarPorDefecto($valor)
{
    return ($valor == 'default' ? null : $valor);

} // function comprobarPorDefecto($valor)