<?php
/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

namespace php\clases;

require_once 'php/autoload.php';
use \Exception;

abstract class Genero
{
    use \php\traits\Validar;

    const ERROR_ALL            = 'No se ha podido encontrar el/los género/s correctamente';
    const ERROR_BUSCADOR       = 'No se ha podido buscar el género con ese nombre correctamente';
    const ERROR_CONTAR_GENEROS = 'No se han podido contar los generos correctamente';

    const NOT_FOUND_ID     = 'No existe un género con ese identificador';
    const NOT_FOUND_NOMBRE = 'No existe un género con ese nombre';
    const NOT_FOUND        = 'No existe ese género';

    const ERRORES_ID_OBLIGATORIO     = 'El id del género debe ser obligatorio';
    const ERRORES_NOMBRE_OBLIGATORIO = 'El nombre del género debe ser obligatorio';
    const ERRORES_NOMBRE_LARGO       = 'El nombre del género es demasiado largo';

    const LIMITE_BUSQUEDA = 3;

    // Metodos //
    private static function validarNombre($sNombre)
    {
        if ($sNombre == null){
            return self::ERRORES_NOMBRE_OBLIGATORIO;
        } else if (mb_strlen($sNombre) > 255){
            return self::ERRORES_NOMBRE_LARGO;
        }

    } // public static function validarNombre($sNombre)

    private static function validarId($iId)
    {
        if ($iId != '0' && $iId == null){
            return self::ERRORES_ID_OBLIGATORIO;
        }

    } // public static function validarNombre($sNombre)

    public static function validar($aDatos){
        $aFunciones = [
            'id'     => 'self::validarId',
            'nombre' => 'self::validarNombre'
        ];

        return self::validarDatos($aDatos, $aFunciones);

    } // public static validar($aDatos)

    public static function datosMixed($aCampos = [])
    {
        $oTabla = new Tabla();

        $sql = 'SELECT * FROM '.TABLA_GENEROS;

        if (!empty($aCampos)){
            $sClave = array_keys($aCampos)[0];

            switch ($sClave){
                case ':id':
                    $sql = 'SELECT * FROM '.TABLA_GENEROS.' WHERE "id" = :id';
                    break;

                case ':nombre':
                    $sql = 'SELECT * FROM '.TABLA_GENEROS.' WHERE "nombre" = :nombre';
                    break;

                default: throw new Exception(self::ERROR_ALL); break;

            } // switch ($sClave)

        } // if (!empty($aCampos))

        $stm = $oTabla->DBsql($sql, self::ERROR_ALL, $aCampos);

        $row = (!empty($aCampos) ? $oTabla->consultaUnicaArr($stm, true, self::NOT_FOUND)
                                 : $oTabla->consultaMultipleArr($stm, true, self::NOT_FOUND));

        return $row;

    } // public static function datosMixed($aCampos)

    public static function totalGeneros($sNombre = null)
    {
        $oTabla = new Tabla();

        $sSql    = 'SELECT COUNT(*) FROM '.TABLA_GENEROS;
        $aCampos = [];

        if ($sNombre != null){
            $sSql    .= ' WHERE "nombre" ILIKE :nombre';
            $aCampos  = [':nombre' => $sNombre];

        } // if ($sNombre != null)

        $stm      = $oTabla->DBsql($sSql, self::ERROR_CONTAR_GENEROS, $aCampos);
        $nGeneros = $oTabla->consultaUnicaObj($stm, true, self::ERROR_CONTAR_GENEROS)->count;

        return $nGeneros;

    } // public static function totalGeneros()

    public static function buscadorNombre($sNombre, $sOrden, $sDireccion, $iOffset)
    {
        $oTabla = new Tabla();

        $stm = $oTabla->DBsql('SELECT "id", "nombre"
                                 FROM '.TABLA_GENEROS.' WHERE "nombre" ILIKE :nombre ORDER BY "'.$sOrden.'" '.$sDireccion.' LIMIT :limit OFFSET :offset',

                              self::ERROR_BUSCADOR,
                              [':nombre' => '%'.$sNombre.'%', ':limit' => self::LIMITE_BUSQUEDA, ':offset' => $iOffset]);

        $row = $oTabla->consultaMultipleArr($stm);

        return $row;

    } // public static function buscadorNombre($sNombre)

    /////////////// - FIN - Metodos

} // class Genero
