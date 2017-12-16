<?php
/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

namespace php\clases;

include_once 'php/config/DB.php';
require_once 'php/autoload.php';

use \PDO;
use \Exception;

final class Tabla
{
    private $_db;

    public function __construct()
    {
        $this->DBcreate();
    }

    // Accesores //
    private function getDB(){ return $this->_db; }

    /////////////// - FIN - Accesores

    // Mutadores //
    private function DBcreate()
    {
        $this->_db = new PDO(DB_DSN, DB_USUARIO, DB_CLAVE);
    }

    /////////////// - FIN - Mutadores

    // Metodos //
    public function DBsql($sql, $error, $aCampos = [])
    {
        try {
            $stm = $this->getDB()->prepare($sql);

            foreach ($aCampos as $campo => $valor){
                $stm->bindValue($campo, $valor);
            } // foreach ($aCampos as $campo => $valor)

            $bSQL   = $stm->execute();
            $aError = $stm->errorInfo();
            $sCode  = $aError[0];

            $sErrorCode = '';

            //var_dump($aError); die();

            if (!$bSQL){
                switch ($sCode){
                    case '23503': $sErrorCode .= '. No se puede borrar y/o actualizar un dato en uso'; break;
                }

                throw new Exception($error . $sErrorCode);

            } // if (!$bSQL)

        } catch (PDOException $e){
            throw new Exception('No se ha podido establecer conexión con la base de datos');
        } // catch (Exception $e)

        return $stm;

    } // public function DBsql()

    public function consultaUnicaArr($stm, $bLanzarException = false, $error = 'No se ha podido realizar la consulta')
    {
        $row = $stm->fetch(PDO::FETCH_ASSOC);

        if ($bLanzarException){
            if (!$row){
                throw new Exception($error);
            }

        } // if ($bLanzarException)

        return $row;

    } // public function consultaUnicaArr($stm, $bLanzarException = false, $error = 'No se ha podido realizar la consulta')

    public function consultaUnicaObj($stm, $bLanzarException = false, $error = 'No se ha podido realizar la consulta')
    {
        $row = $stm->fetchObject();

        if ($bLanzarException){
            if (!$row){
                throw new Exception($error);
            }

        } // if ($bLanzarException)

        return $row;

    } // public function consultaUnicaObj($stm, $error = '')

    public function consultaMultipleObj($stm, $bLanzarException = false, $error = 'No se ha podido realizar la consulta')
    {
        $aResultado = [];

        while ($row = $stm->fetchObject()){
            $aResultado[] = $row;
        }

        if ($bLanzarException){
            if (empty($aResultado)){
                throw new Exception($error);
            }

        } // if ($bLanzarException)

        return $aResultado;

    } // public function consultaMultipleObj($stm, $error = '')

    public function consultaMultipleArr($stm, $bLanzarException = false, $error = 'No se ha podido realizar la consulta')
    {
        $aResultado = [];

        while ($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $aResultado[] = $row;
        }

        if ($bLanzarException){
            if (empty($aResultado)){
                throw new Exception($error);
            }

        } // if ($bLanzarException)

        return $aResultado;

    } // public function consultaMultipleObj($stm, $error = '')

    public function bFilasAfectadas($stm)
    {
        return ($stm->rowCount() > 0);
    }

    public function separarCampos($aCampos){
        $sCampos = (count($aCampos) == 0 ? '*' : '');

        for ($i = 0; $i < count($aCampos); $i++){
            $sCampos .= '"'.$aCampos[$i].'"' . ($i == (count($aCampos) - 1) ? '' : ', ');
        } // for ($i = 0; $i < count($aCampos); $i++)

        return $sCampos;

    } // public function separarCampos($aCampos)

    ////////////// - FIN - Metodos

} // final class Tabla
