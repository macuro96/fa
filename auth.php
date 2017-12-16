<?php
require_once 'php/autoload.php';

use \php\clases\Sesion;

if (!Sesion::existeSesionUsuario()){
    Sesion::volverIndexMensaje('Usuario no identificado');
}
