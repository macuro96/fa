<?php
/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

require_once 'php/funciones.php';

spl_autoload_register(function ($sNombreImp){
    //var_dump($sNombreImp); die();

    $sNombreImpRep = str_replace('\\', '/', $sNombreImp);
    $aDivision     = explode('/', $sNombreImpRep);

    $aRutaVphp = array_count_values($aDivision);
    $nRutaPHP  = isset($aRutaVphp['php']) ? $aRutaVphp['php'] : 1;

    if ($nRutaPHP > 1){
        for ($i = 0; $i < count($aDivision); $i++){
            $aRutaVphp = array_count_values($aDivision);
            $nRutaPHP  = $aRutaVphp['php'];

            if ($aDivision[0] != 'php' || $nRutaPHP > 1){
                array_splice($aDivision, 0, 1);
            } else {
                $i = count($aDivision);
            }

        } // for ($i = 0; $i < count($aDivision); $i++)

    } // if ($nRutaPHP > 1)

    $sRuta = implode('/', $aDivision);

    require_once $sRuta.'.php';

});
