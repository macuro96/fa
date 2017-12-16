<?php
/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

namespace php\traits;

trait Validar
{
    private static function validarDatos(array $aDatos, array $aFunciones)
    {
        $aFuncionesValidacion = array_intersect_key($aFunciones, $aDatos);
        $aErrores             = [];

        foreach ($aFuncionesValidacion as $key => $nombreF){
            $parametro = $aDatos[$key];

            $sError = call_user_func($nombreF, $parametro);

            if ($sError != null){
                $aErrores[$key] = $sError;
            }

        } // foreach ($aFuncionesValidacion as $key => $nombreF)

        return $aErrores;

    } // public static function validarDatos($aDatos)

} // trait Validar
