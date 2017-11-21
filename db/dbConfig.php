<?php

define ('DB_DRIVER', 'pgsql');
define ('DB_HOST', 'localhost');
define ('DB_PORT', 5432);
define ('DB_BD', 'fa');

define ('DB_USUARIO', 'filmaff');
define ('DB_PASSWORD', 'fa');

define ('DB_DSN', DB_DRIVER.":host=".DB_HOST.";"."port=".DB_PORT.";"."dbname=".DB_BD);
