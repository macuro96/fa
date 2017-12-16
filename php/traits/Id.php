<?php
/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

namespace php\traits;

trait Id
{
    private $_iId;

    public function getId(){ return $this->_iId; }

    private function setId($iId)
    {
        $this->_iId = $iId;
    }

} // trait Id
