<?php

use lithium\data\Connections;

Connections::add('default', array(
	'type' => 'database',
	'adapter' => 'MySQL',
	'host' => 'localhost',
	'login' => 'root',
	'password' => 'root',
	'database' => 'cmi',
	'encoding' => 'UTF-8'
));