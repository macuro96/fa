<?php
require_once 'php/F_Session.php';
SessionCrear();

require_once 'php/F_DB.php';

$id = trim(filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT));

try {
    $bBorrarPelicula = DBborrarPelicula($id)['salida'];
    SessionMensajeModificar('La película se ha borrado correctamente');
} catch (Exception $e){
    SessionMensajeModificar($e->getMessage());
}

header('Location: index.php');