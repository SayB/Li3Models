<?php

require __DIR__ . '/Li3Models.php';

use Li3Models\Li3Models;

$Users = Li3Models::model('Users');

$user = $Users::first();

var_dump($user->to('array'));

$user = Li3Models::run('Users', 'all', array('conditions' => array(
	'Users.email' => 'sohaib.muneer@gmail.com'
)));

var_dump($user->to('array'));

use Li3Models\models\Users;

$r = Users::first();

var_dump($r->to('array'));
