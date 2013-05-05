<?php

namespace Li3Models\models;

use lithium\util\String;
use lithium\security\Password;
use lithium\util\Validator;

Validator::add('confirmPassword', function ($pass, $format, $options) {
	$data = $options['values'];
	if (!array_key_exists('confirm_password', $data)) {
		return true;
	}
	return $data['password'] == $data['confirm_password'];
});

class Users extends \Li3Models\extensions\data\AppModel {

	public $validates = array(
		'first_name' => array(
			array(
				'notEmpty',
				'message' => 'Please provide a first name.'
			)
		),
		'last_name' => array(
			array(
				'notEmpty',
				'message' => 'Please provide a last name.'
			)
		),
		'email' => array(
			array(
				'email',
				'message' => 'Please provide a valid email address.'
			)
		),
		'password' => array(
			array(
				'lengthBetween',
				'options' => array(
					'min' => 6,
					'max' => 99999
				),
				'message' => 'Please enter a password at least six characters long',
				'skipEmpty' => true
			),
			array(
				'confirmPassword',
				'message' => 'Passwords do not match',
				'skipEmpty' => true
			)
		)
	);

	public function name($entity) {
		$name = trim($entity->first_name . ' ' . $entity->last_name);
		return empty($name) ? 'None' : $name;
	}

	public function isActive($entity) {
		return $entity->active ? 'Yes' : 'No';
	}
}

// {{{ Filters
Users::applyFilter('save', function ($self, $params, $chain) {
	$entity = $params['entity'];

	if ($entity->id) {
		$user = User::first(array('conditions' => array('User.id' => $entity->id)));
		if ($entity->password == $user->password) {
			return $chain->next($self, $params, $chain);
		}

		if (empty($entity->password) && empty($entity->confirm_password)) {
			$entity->password = $user->password;
			$params['entity'] = $entity;
			$params['data']['password'] = $user->password;
			$params['data']['confirm_password'] = $user->password;
			return $chain->next($self, $params, $chain);
		}
	}

	$salt = \lithium\core\Environment::get('salt');
	$pass = \lithium\security\Password::hash($entity->password, $salt);
	$entity->password = $pass;

	if (isset($entity->confirm_password)) {
		$entity->confirm_password = $pass;
	}

	$data = $entity->to('array');
	$params['data'] = $data;
	$params['entity'] = $entity;

	return $chain->next($self, $params, $chain);
});
// }}}
