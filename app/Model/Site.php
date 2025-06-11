<?php
App::uses('AppModel', 'Model');
/**
 * Site Model
 *
 * @property Reservation $Reservation
 * @property Volreservation $Volreservation
 */
class Site extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'nom';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Reservation' => array(
			'className' => 'Reservation',
			'foreignKey' => 'site_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Volreservation' => array(
			'className' => 'Volreservation',
			'foreignKey' => 'site_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
