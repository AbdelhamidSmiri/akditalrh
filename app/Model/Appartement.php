<?php
App::uses('AppModel', 'Model');
/**
 * Appartement Model
 *
 * @property Beneficiaire $Beneficiaire
 */
class Appartement extends AppModel {

	public $displayField = 'nom';
	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Beneficiaire' => array(
			'className' => 'Beneficiaire',
			'foreignKey' => 'appartement_id',
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
