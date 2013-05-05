<?php
/**
 * The prupose of this class is to encapsulate Lithium's Model layer and make it
 * available in any application so that you could enjoy all the goodness that is
 * offered by Lithium's Model layer :)
 *
 * @author Sohaib Muneer
 *
 */
namespace Li3Models;

require __DIR__ . '/config/boostrap.php';

use lithium\core\Libraries;

class Li3Models {

	public static function model($model) {
		return Libraries::locate('models', $model);
	}

	public static function modelInstance($model = null) {
		if (!$model) {
			return false;
		}

		$m = Libraries::locate('models', $model);
		if (empty($m)) {
			return null;
		}

		return $m::object();
	}

	public static function run($model, $staticMethod, $options = null) {
		$m = static::modelInstance($model);
		return $m::$staticMethod($options);
	}
}
