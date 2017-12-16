<?php
/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

namespace php\clases;

require_once 'php/autoload.php';
use \Exception;

class Validacion
{
    use \php\traits\Validar { validarDatos as public; }
}
