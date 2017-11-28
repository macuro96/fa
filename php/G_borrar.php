<?php

/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

require_once 'php/F_Session.php'; SessionAuth();
require_once 'php/F_DB.php';

$id = trim(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));

try {
    $row    = DBpeliculaId($id);
    $titulo = $row->titulo;

} catch (Exception $e){
    volverIndexMensaje($e);
} // catch (Exception $e)