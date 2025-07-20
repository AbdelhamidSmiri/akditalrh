<?php
App::uses('AppModel', 'Model');
/**
 * Hotel Model
 *
 * @property Hotelprice $Hotelprice
 * @property Reservation $Reservation
 */
class Chambre extends AppModel
{

	/**
	 * Display field
	 *
	 * @var string
	 */
	public $displayField = 'nom';
	public $actsAs = array('Containable');


	// The Associations below have been created with all possible keys, those that are not needed can be removed



	public $belongsTo = array(
		'Hotel' => array(
			'className' => 'Hotel',
			'foreignKey' => 'hotel_id'
		)
	);
	/**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array(
		'Hotelprice' => array(
			'className' => 'Hotelprice',
			'foreignKey' => 'chambre_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Reservation' => array(
			'className' => 'Reservation',
			'foreignKey' => 'chambre_id',
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
