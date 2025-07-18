<?php
App::uses('AppModel', 'Model');
/**
 * Appartement Model
 *
 * @property Ville $Ville
 * @property Beneficiaire $Beneficiaire
 */
class Appartement extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Ville' => array(
			'className' => 'Ville',
			'foreignKey' => 'ville_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

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
