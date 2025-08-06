<?php
App::uses('AppModel', 'Model');
/**
 * Beneficiaire Model
 *
 * @property Site $Site
 * @property User $User
 * @property Appartement $Appartement
 */
class Beneficiaire extends AppModel {

	public $displayField = 'nom';
	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Site' => array(
			'className' => 'Site',
			'foreignKey' => 'site_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Appartement' => array(
			'className' => 'Appartement',
			'foreignKey' => 'appartement_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
