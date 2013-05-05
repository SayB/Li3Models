<?php

namespace Li3Models\models;

use lithium\util\Validator;

class Project extends \Li3Models\extensions\data\AppModel {

	public $validates = array(
		'name' => array(
			array(
				'isUnique',
				'message' => 'Project name already exists. Please provide another one.'
			)
		)
	);

}

