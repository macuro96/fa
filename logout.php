<?php

/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

require_once 'php/F_Session.php'; SessionAuth();

try {
    SessionCerrarSesionUsuario();
} catch (Exception $e){
    SessionMensajeModificar($e->getMessage());
}

header('Location: index.php');
exit;