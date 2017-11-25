<?php

function h($cadena)
{
    return htmlspecialchars($cadena, ENT_QUOTES | ENT_SUBSTITUTE);

} // function h($cadena)

function filtroValido($sComprobar)
{
    return ($sComprobar != null && $sComprobar !== false);

} // function filtroValido($sComprobar)