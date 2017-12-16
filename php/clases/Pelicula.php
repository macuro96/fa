<?php
/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

namespace php\clases;

require_once 'php/autoload.php';
use \Exception;

abstract class Pelicula
{
    use \php\traits\Validar;

    const ERROR_BUSCADOR         = 'No se ha podido buscar la película con ese título correctamente';
    const ERROR_IDENTIFICADOR    = 'No se ha podido encontrar la película con ese identificador correctamente';
    const ERROR_CONTAR_PELICULAS = 'No se han podido contar las películas correctamente';

    const NOT_FOUND_ID        = 'No existe una película con ese identificador';

    const ERRORES_TITULO_OBLIGATORIO = 'El título es obligatorio';
    const ERRORES_TITULO_LARGO       = 'El título es demasiado largo';
    const ERRORES_ANYO               = 'El año no es válido';
    const ERRORES_DURACION           = 'La duración no es válida';

    const LIMITE_BUSQUEDA = 5;

    // Metodos //
    private static function validarTitulo($sTitulo)
    {
        if ($sTitulo == null){
            return self::ERRORES_TITULO_OBLIGATORIO;
        } else if (mb_strlen($sTitulo) > 255){
            return self::ERRORES_TITULO_LARGO;
        }

    } // private static function validarTitulo($sTitulo)

    private static function validarSipnosis(){}

    private static function validarAnyo($iAnyo)
    {
        if ($iAnyo != null){
            if (filter_var($iAnyo, FILTER_VALIDATE_INT, [
                'options' => [
                    'min_range' => 0,
                    'max_range' => 9999
                ]
            ]) === false){ return self::ERRORES_ANYO; }

        } // if ($iAnyo != null)

    } // private static function validarAnyo(iAnyo)

    private static function validarDuracion($iDuracion)
    {
        if ($iDuracion != null){
            if (filter_var($iDuracion, FILTER_VALIDATE_INT, [
            'options' => [
                'min_range' => 0,
                'max_range' => 9999
            ]]) === false) { return self::ERRORES_DURACION; }

        } // if ($iDuracion != null)

    } // private static function validarDuracion($iDuracion)

    public static function validar($aDatos)
    {
        $aFunciones = [
            'titulo'   => 'self::validarTitulo',
            'sipnosis' => 'self::validarSipnosis',
            'anyo'     => 'self::validarAnyo',
            'duracion' => 'self::validarDuracion'
        ];

        return self::validarDatos($aDatos, $aFunciones);

    } // public static function validar($aDatos)

    public static function totalPeliculas($sCadenaBuscador = null, $sFiltro = null)
    {
        $oTabla = new Tabla();

        $sSql    = 'SELECT COUNT(*) FROM '.VISTA_PELICULAS;
        $aCampos = [];

        if ($sCadenaBuscador != null && $sFiltro != null){
            $aFiltrosNumericos = ['duracion', 'anyo'];

            $sSql                 .=  ' WHERE "'.$sFiltro.'" '.(in_array($sFiltro, $aFiltrosNumericos) ? '=' : 'ILIKE') . " :$sFiltro";
            $aCampos[":$sFiltro"]  = (in_array($sFiltro, $aFiltrosNumericos) ? $sCadenaBuscador : "%$sCadenaBuscador%");

        } // if ($sCadenaBuscador != null && $sFiltro != null)

        $stm        = $oTabla->DBsql($sSql, self::ERROR_CONTAR_PELICULAS, $aCampos);
        $nPeliculas = $oTabla->consultaUnicaObj($stm, true, self::ERROR_CONTAR_PELICULAS)->count;

        return $nPeliculas;

    } // public static function totalPeliculas()

    public static function buscador($sCadenaBuscador, $sFiltro, $sOrden, $sDireccion, $iOffset)
    {
        $oTabla = new Tabla();

        $sql = 'SELECT "id", "titulo", left("sipnosis", 40) AS "sipnosis", "anyo", "duracion", "genero", "genero_id"
                  FROM '.VISTA_PELICULAS;

        $aCampos = ['limit' => self::LIMITE_BUSQUEDA, 'offset' => $iOffset];

        $aFiltrosNumericos = ['duracion', 'anyo'];

        if ($sCadenaBuscador != null){
            $sql .=  'WHERE "'.$sFiltro.'" '.(in_array($sFiltro, $aFiltrosNumericos) ? '=' : 'ILIKE') . " :$sFiltro";

            $aCampos[":$sFiltro"] = (in_array($sFiltro, $aFiltrosNumericos) ? $sCadenaBuscador : "%$sCadenaBuscador%");

        } // if ($sCadenaBuscador != null)

        $sql .= " ORDER BY \"$sOrden\" $sDireccion LIMIT :limit OFFSET :offset";

        $stm = $oTabla->DBsql($sql,
                              self::ERROR_BUSCADOR,
                              $aCampos);

        $row = $oTabla->consultaMultipleArr($stm);

        return $row;

    } // public static function buscador($sCadenaBuscador, $sFiltro)

    /**
     * Proporciona los datos de una pelicula dada una Id.
     * @param  int          $iId Id de la pelicula
     * @return PDOStatment       Devuelve un objeto con los datos de la pelicula
     */
    public static function datosId($iId)
    {
        $oTabla = new Tabla();

        $stm = $oTabla->DBsql('SELECT * FROM '.VISTA_PELICULAS.' WHERE "id" = :id',

                              self::ERROR_IDENTIFICADOR,
                              [':id' => $iId]);

        $row = $oTabla->consultaUnicaArr($stm, true, self::NOT_FOUND_ID);

        return $row;

    } // public static function datosId($iId)

    /////////////// - FIN - Metodos

} // abstract class Pelicula
