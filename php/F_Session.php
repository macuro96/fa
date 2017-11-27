<?php

/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

function SessionCrear()
{
    session_name('fa');
    session_start();

} // function SessionCrear()

function SessionExiste(){
    return (session_id() != '');

} // function SessionExiste()

function SessionCerrar()
{
    if (SessionExiste()){
        session_destroy();
    } else { // 
        throw Exception("No existe ninguna sesión para cerrar");
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

function SessionMensajeExiste(){
    return isset($_SESSION['mensaje']);
}

function SessionMensaje(){
    return (SessionMensajeExiste() ? $_SESSION['mensaje'] : null);
}

function notificacionMensaje(){
    if (SessionExiste() && SessionMensajeExiste()): ?>    
        <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Mensaje:</strong> <?= h(SessionMensaje()) ?>
        </div>        
        <?php SessionMensajeBorrar();        

    endif;

} // function notificacionMensaje()