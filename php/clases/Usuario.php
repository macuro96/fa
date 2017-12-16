<?php
/**
 * @author Manuel Cuevas Rodriguez
 * @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
 * @license https://www.gnu.org/licenses/gpl.txt
 */

namespace php\clases;

require_once 'php/autoload.php';

use \php\clases\Sesion;
use \Exception;

final class Usuario
{
    use \php\traits\Id, \php\traits\Validar;

    private $_sNombre;
    private $_sPassword;

    private $_bSesionIniciada = false;

    const ERROR_NOMBRE             = 'No se ha podido buscar ese usuario correctamente';
    const ERROR_SESION_INICIADA    = 'La sesión ya está iniciada';
    const ERROR_SESION_NO_INICIADA = 'La sesión no está iniciada';

    const ERROR_INSERTAR_PELICULA  = 'No se ha podido añadir la película correctamente';
    const ERROR_MODIFICAR_PELICULA = 'No se ha podido modificar la película correctamente';
    const ERROR_BORRAR_PELICULA    = 'No se ha podido borrar la película correctamente';

    const ERROR_INSERTAR_GENERO  = 'No se ha podido añadir el género correctamente';
    const ERROR_MODIFICAR_GENERO = 'No se ha podido modificar el género correctamente';
    const ERROR_BORRAR_GENERO    = 'No se ha podido borrar el género correctamente';

    const ERROR_MODIFICAR_DATOS    = 'No se ha podido modificar los datos del usuario correctamente';
    const ERROR_MODIFICAR_NOMBRE   = 'Ya existe un usuario con ese nombre';

    const NOT_FOUND    = 'No existe ningún usuario con ese nombre y/o contraseña';

    const ERRORES_NOMBRE_OBLIGATORIO    = 'El nombre de usuario es obligatorio';
    const ERRORES_NOMBRE_LARGO          = 'El nombre de usuario es demasiado largo';
    const ERRORES_NOMBRE_ESPACIOS       = 'El nombre de usuario no puede contener espacios';
    const ERRORES_PASSWORD_OBLIGATORIO  = 'La contraseña es obligatoria';
    const ERRORES_CONF_PASSWORD         = 'Las contraseñas no son iguales';
    const ERRORES_CONF_NOMBRE           = 'Los nombres no son iguales';

    public function __construct($sNombre, $sPassword)
    {
        $this->setNombre($sNombre);
        $this->setPassword($sPassword);

    } // public function __construct($sNombre, $sPassword)

    // Accesores //
    public  function getNombre()     { return $this->_sNombre;         }
    private function getPassword()   { return $this->_sPassword;       }
    private function sesionIniciada(){ return $this->_bSesionIniciada; }

    /////////////// - FIN - Accesores

    // Mutadores //
    private function setNombre($sNombre)
    {
        $this->_sNombre = $sNombre;
    } // private function setNombre($sNombre)

    private function setPassword($sPassword)
    {
        $this->_sPassword = $sPassword;
    } // private function setPassword($sPassword)

    /////////////// - FIN - Mutadores

    // Metodos //
    private static function validarNombre($sNombre)
    {
        if ($sNombre != null){
            if (mb_strlen($sNombre) > 255){
                return self::ERRORES_NOMBRE_LARGO;
            }

            if (mb_strpos($sNombre, ' ') !== false){
                return self::ERRORES_NOMBRE_ESPACIOS;
            }

        } else { // if ($sNombre == null)
            return self::ERRORES_NOMBRE_OBLIGATORIO;

        } // else ($sNombre == null)

    } // private function validarNombre()

    private static function validarPassword($sPassword)
    {
        if ($sPassword == null){
            return self::ERRORES_PASSWORD_OBLIGATORIO;
        }

    } // private function validarNombre()

    private static function validarConfirmarPassword($aDatos)
    {
        if ($aDatos['password'] != null && $aDatos['confirmarPassword'] != null){
            if ($aDatos['password'] != $aDatos['confirmarPassword']){
                return self::ERRORES_CONF_PASSWORD;
            }

        } // if ($aDatos['password'] != null && $aDatos['confirmarPassword'] != null)

    } // private static function validarConfirmarPassword($sPassword, $sPasswordConf)

    private static function validarConfirmarNombre($aDatos)
    {
        if ($aDatos['nombre'] != null && $aDatos['confirmarNombre'] != null){
            if ($aDatos['nombre'] != $aDatos['confirmarNombre']){
                return self::ERRORES_CONF_NOMBRE;
            }

        } // if ($aDatos['password'] != null && $aDatos['confirmarNombre'] != null)

    } // private static function validarConfirmarNombre($sNombre, $sNombreConf)

    public static function validar($aDatos)
    {
        $aFunciones = [
                'nombre'            => 'self::validarNombre',
                'password'          => 'self::validarPassword',
                'confirmarPassword' => 'self::validarConfirmarPassword',
                'confirmarNombre'   => 'self::validarConfirmarNombre'
        ];

        return self::validarDatos($aDatos, $aFunciones);

    } // public function validarDatos($aDatos)

    private function comprobarInicioSesion($bIniciada = false)
    {
        if ($this->sesionIniciada() == $bIniciada){
            throw new Exception(self::ERROR_SESION_INICIADA);
        }

    } // private function comprobarInicioSesion()

    public static function auth($sNombre, $sPassword)
    {
        $oTabla = new Tabla();

        $stm = $oTabla->DBsql('SELECT * FROM '.TABLA_USUARIOS.' WHERE "nombre" = :nombre',

                             self::ERROR_NOMBRE,
                             [':nombre' => $sNombre]);

        $row               = $oTabla->consultaUnicaObj($stm, true, self::NOT_FOUND);
        $bPasswordCorrecto = password_verify($sPassword, $row->password);

        if (!$bPasswordCorrecto){
            throw new Exception(self::NOT_FOUND);
        }

        return $row;

    } // static function auth($sNombre, $sPassword)

    public function iniciarSesion()
    {
        $this->comprobarInicioSesion(true);

        $usuario = self::auth($this->getNombre(), $this->getPassword());
        $this->setId($usuario->id);

        $this->_bSesionIniciada = true;
        Sesion::iniciarSesionUsuario($this);

    } // public function iniciarSesion()

    public function cerrarSesion()
    {
        $this->comprobarInicioSesion();

        $this->_bSesionIniciada = false;
        Sesion::cerrarSesionUsuario();

    } // public function cerrarSesion()

    private function accionSQL($oTabla, $sSql, $sError, $aCampos)
    {
        $this->comprobarInicioSesion();

        $stm     = $oTabla->DBsql($sSql, $sError, $aCampos);
        $bAccion = $oTabla->bFilasAfectadas($stm);

        if (!$bAccion){
            throw new Exception($sError);
        }

    } // private function accionSQL($sSql, $sError, $aCampos)

    public function modificarPassword($sPassword)
    {
        $oTabla = new Tabla();

        $sSql = 'UPDATE '.TABLA_USUARIOS.'
                    SET "password" = :password
                  WHERE "id" = :id';

        $sError  = self::ERROR_MODIFICAR_DATOS;
        $aCampos = [
            ':password' => password_hash($sPassword, PASSWORD_DEFAULT, ['cost' => 10]),
            ':id'       => $this->getId()
        ];

        $this->accionSQL($oTabla, $sSql, $sError, $aCampos);
        $this->setPassword($sPassword);

    } // public function modificarPassword($sPassword)

    public function modificarNombre($sNombre)
    {
        $this->comprobarInicioSesion();

        $oTabla = new Tabla();

        $sSql = 'UPDATE '.TABLA_USUARIOS.'
                    SET "nombre" = :nombre
                  WHERE "id"     = :id AND :nombre != (SELECT "nombre" FROM '.TABLA_USUARIOS.' WHERE "nombre" = :nombreUsuario)';

        $aCampos = [
            ':nombre'        => $sNombre,
            ':id'            => $this->getId(),
            ':nombreUsuario' => $this->getNombre()
        ];

        $stm     = $oTabla->DBsql($sSql, self::ERROR_MODIFICAR_DATOS, $aCampos);
        $bUpdate = $oTabla->bFilasAfectadas($stm);

        if (!$bUpdate){
            throw new Exception(self::ERROR_MODIFICAR_NOMBRE);
        }

        $this->setNombre($sNombre);

    } // public function modificarNombre($sNombre)

    public function insertarPelicula($sTitulo, $iAnyo, $sSipnosis, $iGenero_id, $iDuracion)
    {
        $oTabla = new Tabla();

        $sSql   = 'INSERT INTO '.TABLA_PELICULAS.' ("titulo", "anyo", "sipnosis", "genero_id", "duracion")
                      VALUES (:titulo, :anyo, :sipnosis, :genero_id, :duracion)';

        $sError  = self::ERROR_INSERTAR_PELICULA;
        $aCampos = [
            ':titulo'    => $sTitulo,
            ':anyo'      => $iAnyo,
            ':sipnosis'  => $sSipnosis,
            ':genero_id' => $iGenero_id,
            ':duracion'  => $iDuracion
        ];

        $this->accionSQL($oTabla, $sSql, $sError, $aCampos);

    } // public function insertarPelicula($sTitulo, $iAnyo, $sSipnosis, $iGenero_id, $iDuracion)

    public function insertarGenero($sNombre)
    {
        $oTabla = new Tabla();

        $sSql   = 'INSERT INTO '.TABLA_GENEROS.' ("nombre")
                        VALUES (:nombre)';

        $sError  = self::ERROR_INSERTAR_GENERO;
        $aCampos = [
            ':nombre' => $sNombre
        ];

        $this->accionSQL($oTabla, $sSql, $sError, $aCampos);

    } // public function insertarPelicula($sTitulo, $iAnyo, $sSipnosis, $iGenero_id, $iDuracion)

    public function modificarPelicula($iId, $sTitulo, $iAnyo, $sSipnosis, $iGenero_id, $iDuracion)
    {
        $oTabla = new Tabla();

        $sSql = 'UPDATE '.TABLA_PELICULAS.'
                    SET "titulo" = :titulo, "anyo" = :anyo, "sipnosis" = :sipnosis, "genero_id" = :genero_id, "duracion" = :duracion
                  WHERE "id" = :id';

        $sError  = self::ERROR_MODIFICAR_PELICULA;
        $aCampos = [
            ':id'        => $iId,
            ':titulo'    => $sTitulo,
            ':anyo'      => $iAnyo,
            ':sipnosis'  => $sSipnosis,
            ':genero_id' => $iGenero_id,
            ':duracion'  => $iDuracion
        ];

        $this->accionSQL($oTabla, $sSql, $sError, $aCampos);

    } // public function modificarPelicula($iId, $sTitulo, $iAnyo, $sSipnosis, $iGenero_id, $iDuracion)

    public function modificarGenero($iId, $sNombre)
    {
        $oTabla = new Tabla();

        $sSql = 'UPDATE '.TABLA_GENEROS.'
                    SET "nombre" = :nombre
                  WHERE "id" = :id';

        $sError  = self::ERROR_MODIFICAR_GENERO;
        $aCampos = [
            ':id'     => $iId,
            ':nombre' => $sNombre,
        ];

        $this->accionSQL($oTabla, $sSql, $sError, $aCampos);

    } // public function modificarPelicula($iId, $sTitulo, $iAnyo, $sSipnosis, $iGenero_id, $iDuracion)

    public function borrarPelicula($iId)
    {
        $oTabla  = new Tabla();

        $sSql    = 'DELETE FROM '.TABLA_PELICULAS.' WHERE "id" = :id';
        $sError  = self::ERROR_BORRAR_PELICULA;
        $aCampos = [':id' => $iId];

        $this->accionSQL($oTabla, $sSql, $sError, $aCampos);

    } // public function borrarPelicula($iId)

    public function borrarGenero($iId)
    {
        $oTabla  = new Tabla();

        $sSql    = 'DELETE FROM '.TABLA_GENEROS.' WHERE "id" = :id';
        $sError  = self::ERROR_BORRAR_GENERO;
        $aCampos = [':id' => $iId];

        $this->accionSQL($oTabla, $sSql, $sError, $aCampos);

    } // public function borrarPelicula($iId)

    /////////////// - FIN - Metodos

} // class Usuario
