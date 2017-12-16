<?php
/**
* @author Manuel Cuevas Rodriguez
* @copyright Copyright (c) 2017 Manuel Cuevas Rodriguez
* @license https://www.gnu.org/licenses/gpl.txt
*/

namespace php\config;

define ('DB_DRIVER', 'pgsql');
define ('DB_HOST', 'localhost');
define ('DB_PORT', 5432);
define ('DB_BD', 'fa');

define ('DB_USUARIO', 'filmaff');
define ('DB_CLAVE', 'fa');

define ('DB_DSN', DB_DRIVER.":host=".DB_HOST.";"."port=".DB_PORT.";"."dbname=".DB_BD);

include_once 'DBconstants.php';
