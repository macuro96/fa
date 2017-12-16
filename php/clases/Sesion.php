<?php
/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

namespace php\clases;

session_name('fa'); session_start();
require_once 'php/autoload.php';

use \Exception;

abstract class Sesion
{
    private static function existe()
    {
        return (session_id() != '');
    }

    public static function cerrar()
    {
        if (self::existe()){
            session_destroy();
        }

    } // public static function cerrar()

    public static function mensaje()
    {
        return (self::existeMensaje() ? $_SESSION['mensaje'] : null);
    }

    public static function iniciarSesionUsuario($oUsuario)
    {
        $_SESSION['Usuario'] = serialize($oUsuario);
    }

    public static function cerrarSesionUsuario()
    {
        unset($_SESSION['Usuario']);
    }

    public static function existeSesionUsuario()
    {
        return isset($_SESSION['Usuario']);
    }

    public static function usuarioSesion()
    {
        if (self::existeSesionUsuario()){
            return $_SESSION['Usuario'];
        }

    } // public static function usuarioSesion()

    private static function existeMensaje()
    {
        return isset($_SESSION['mensaje']);
    }

    public static function modificarMensaje($sMensaje)
    {
        $_SESSION['mensaje'] = $sMensaje;
    }

    public static function borrarMensaje()
    {
        unset($_SESSION['mensaje']);
    }

    public static function notificacionMensaje()
    {
        if (self::existeMensaje()): ?>
            <div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Mensaje:</strong> <?= h(self::mensaje()) ?>
            </div>
        <?php
        endif;

        self::borrarMensaje();

    } // public static function notificacionMensaje($iTipo)

    public static function volverIndexMensaje($e)
    {
        $sModo = input(INPUT_GET, 'modo');

        self::modificarMensaje(($e instanceof Exception) ? $e->getMessage() : $e);
        header('Location: index.php?modo='.$sModo);
        exit;

    } // function volverIndexMensaje($e)

} // abstract class Sesion
