<?php
App::uses('AppModel', 'Model');
/**
 * Hotel Model
 *
 * @property Hotelprice $Hotelprice
 * @property Reservation $Reservation
 */
class Hotel extends AppModel {

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
		'Chambre' => array(
			'className' => 'Chambre',
			'foreignKey' => 'hotel_id',
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
	);

}
