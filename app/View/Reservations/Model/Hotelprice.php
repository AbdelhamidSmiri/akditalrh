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
		'Chambre' => array(
			'className' => 'Chembre',
			'foreignKey' => 'Chambre_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
