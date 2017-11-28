<?php
/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

require_once 'php/F_Session.php'; SessionAuth();
require_once 'php/F_DB.php';

$id = trim(filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT));

try {
    $bBorrarPelicula = DBborrarPelicula($id);    
    SessionMensajeModificar('La película se ha borrado correctamente');

} catch (Exception $e){
    SessionMensajeModificar($e->getMessage());
}

header('Location: index.php');
exit;