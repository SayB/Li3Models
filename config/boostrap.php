<?php

define('DS', '/'); // change it to '\' in case of Windows
//die(dirname(__DIR__));

define('LITHIUM_LIBRARY_PATH', dirname(__DIR__) . DS . 'libraries');
define('LITHIUM_APP_PATH', dirname(__DIR__));

// now load all them files important to run the data layer for Lithium

require __DIR__ . DS . 'libraries.php';
/**
 * Include this file if your application uses one or more database connections.
 */
require __DIR__ . DS . 'connections.php';
