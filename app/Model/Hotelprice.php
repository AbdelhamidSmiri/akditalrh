<?php
App::uses('AppModel', 'Model');
/**
 * Hotelprice Model
 *
 * @property Hotel $Hotel
 */
class Hotelprice extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Hotel' => array(
			'className' => 'Hotel',
			'foreignKey' => 'hotel_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
