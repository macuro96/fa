<?php

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