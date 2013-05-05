<?php

namespace Li3Models\extensions\data;

use lithium\data\Model;
use lithium\util\Validator;

class AppModel extends \lithium\data\Model {

	public static function object() {
		return static::_object();
	}

	public function isActive($entity) {
		if (!isset($entity->active)) return 'N/A';
		if ($entity->active) return 'Yes';

		return 'No';
	}

	public static function activate($id) {
		self::_activate($id);
	}

	public static function deactivate($id) {
		self::_activate($id, 0);
	}

	protected static function _activate($id, $activate = 1) {
		self::update(array('active' => $activate), array('id' => $id));
	}
}
