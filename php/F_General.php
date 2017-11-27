<?php

/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

function h($cadena)
{
    return htmlspecialchars($cadena, ENT_QUOTES | ENT_SUBSTITUTE);

} // function h($cadena)