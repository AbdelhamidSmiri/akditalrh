<?php
App::uses('AppModel', 'Model');
/**
 * Reservation Model
 *
 * @property User $User
 * @property Hotel $Hotel
 * @property Site $Site
 */
class Reservation extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Chambre' => array(
			'className' => 'Chambre',
			'foreignKey' => 'chambre_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Site' => array(
			'className' => 'Site',
			'foreignKey' => 'site_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
