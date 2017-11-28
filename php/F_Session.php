<?php

/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

require_once 'F_General.php';

session_name('fa');
session_start();

function SessionExiste()
{
    return (session_id() != '');

} // function SessionExiste()

function SessionCerrar()
{
    if (SessionExiste()){
        session_destroy();
    }
    
} // function SessionCerrar()

function SessionMensajeModificar($mensaje)
{
    $_SESSION['mensaje'] = $mensaje;
}

function SessionMensajeBorrar()
{
    unset($_SESSION['mensaje']);
}

function SessionMensajeExiste()
{
    return isset($_SESSION['mensaje']);
}

function SessionMensaje()
{
    return (SessionMensajeExiste() ? $_SESSION['mensaje'] : null);
}

function SessionIniciarSesionUsuario($id, $nombre)
{
    $_SESSION['Usuario'] = [
        'id'     => $id,
        'nombre' => $nombre
    ];

} // function SessionIniciarSesionUsuario($id, $nombre)

function SessionCerrarSesionUsuario()
{
    $_SESSION = [];

    $params = session_get_cookie_params();

    setcookie(
        session_name(),         // nombre
        '',                     // valor
        1,                      // tiempo de expiracion
        $params['path'],        // path
        $params['domain'],      // domain
        $params['secure'],      // secure
        $params['httponly']     // httponly
    );

    SessionCerrar();

} // function SessionCerrarSesionUsuario()

function SessionExisteSesionUsuario()
{
    return (isset($_SESSION['Usuario']));

} // function SessionExisteSesionUsuario()

function SessionNombreUsuario()
{
    return $_SESSION['Usuario']['nombre'];

} // function SessionNombreUsuario()

function SessionAuth()
{
    if (!SessionExisteSesionUsuario()){
        volverIndexMensaje('Usuario no identificado');
        exit;
    } 

} // function SessionAuth()

function notificacionMensaje(){
    if (SessionExiste() && SessionMensajeExiste()): ?>    
        <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Mensaje:</strong> <?= h(SessionMensaje()) ?>
        </div>        
        <?php SessionMensajeBorrar();        

    endif;

} // function notificacionMensaje()

function volverIndexMensaje($e)
{
    SessionMensajeModificar(($e instanceof Exception) ? $e->getMessage() : $e);
    header('Location: index.php');       
    exit;

} // function volverIndexMensaje($e)