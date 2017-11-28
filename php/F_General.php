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

function mostrarErrores($errores)
{
    if (!empty($errores)):?>
        <div class="row">
            <div class="col-lg-offset-1 col-lg-3">
                <?php foreach ($errores as $error):?>
                    <h3><?= $error ?></h3>                        
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif;    

} // function mostrarErrores($errores)