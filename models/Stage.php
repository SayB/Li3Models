<?php
namespace Di3\models;

use lithium\util\Validator;
use app\models\StageDependancy;

Validator::add('isUnique', function ($pass, $format, $options) {

	$data = $options['values'];
	$conditions = array(
		'Stage.name' => $data['name'],
		'Stage.project_id' => $data['project_id']
	);

	if (!empty($data['id'])) {
		$conditions['Stage.id']['!='] = $data['id'];
	}

	$count = \app\models\Stage::count(compact('conditions'));

	return $count < 1;
});

Validator::add('dependantStagesCheck', function ($pass, $format, $options) {
	$data = $options['values'];

	if (array_key_exists('follow_sort_order', $data) && $data['follow_sort_order'] == 0) {
		$dependantStages = array();
		foreach ($data as $k => $v) {
			if (substr($k, 0, 18) == 'dependant_stage_id') {
				list(, $dStageId) = explode('.', $k);
				$dependantStages[$dStageId] = $v;
			}
		}

		if (empty($dependantStages)) {
			return false;
		}

		foreach ($dependantStages as $dSId => $checked) {
			if (!empty($checked)) {
				return true;
			}
		}

		return false;
	}

	return true;
});

class Stage extends \Di3\extensions\data\AppModel {

	public $validates = array(
		'name' => array(
			array(
				'notEmpty',
				'message' => 'Please enter a name for this stage.'
			),
			array(
				'isUnique',
				'message' => 'A stage by this name already exists for this project. Please choose a different name.'
			)
		),
		'description' => array(
			array(
				'notEmpty',
				'message' => 'Please give an overview for this stage.'
			)
		),
		'duration' => array(
			array(
				'notEmpty',
				'message' => 'Please give an estimated duration for this stage\'s completion.'
			)
		),
		'cost' => array(
			array(
				'notEmpty',
				'message' => 'Please give an estimated cost for this stage.'
			)
		),
		'follow_sort_order' => array(
			array(
				'dependantStagesCheck',
				'required' => true,
				'message' => 'Please select at least one dependant stage.'
			)
		)
	);

	public function stepCount($entity) {
		if (isset($entity->stepCount)) {
			return $entity->stepCount;
		}

		return $entity->stepCount = \app\models\Step::count(array('conditions' => array(
			'Step.stage_id' => $entity->id
		)));
	}
}

// custom finder to get dependant stages
Stage::finder('dependantStagesList', function ($self, $params, $chain) {
	$data = array();
	$result = StageDependancy::find('all', array(
		'conditions' => array(
			'stage_id' => $params['options']['conditions']['id']
		),
		'fields' => array('id', 'dependant_stage_id')
	));

	foreach ($result as $r) {
		$data[$r->id] = $r->dependant_stage_id;
	}

	$chain->next($self, $params, $chain);
	return $data;
});


// Filter to save dependant stages
Stage::applyFilter('save', function ($self, $params, $chain) {

	$result = $chain->next($self, $params, $chain);
	$entity = $params['entity'];

	$dependantStages = array();
	if ($entity->follow_sort_order == 0) {
		foreach ($entity->dependant_stage_id as $k => $v) {
			if ($v == 1) {
				$dependantStages[] = $k;
			}
		}
	}

	if (empty($dependantStages)) {
		return $result;
	}

	StageDependancy::remove(array('stage_id' => $entity->id));
	foreach ($dependantStages as $ds) {
		$sd = StageDependancy::create(array(
			'stage_id' => $entity->id,
			'dependant_stage_id' => $ds
		));
		$sd->save();
	}

	return $result;
});