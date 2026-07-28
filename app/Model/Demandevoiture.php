<?php
App::uses('AppModel', 'Model');

/**
 * Demandevoiture Model — requêtes véhicule collaborateurs
 */
class Demandevoiture extends AppModel
{
	public $displayField = 'marque';

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
		),
		'BeneficiaireUser' => array(
			'className' => 'User',
			'foreignKey' => 'beneficiaire',
		),
		'Rh' => array(
			'className' => 'User',
			'foreignKey' => 'rh_id',
		),
		'Dgd' => array(
			'className' => 'User',
			'foreignKey' => 'dgd_id',
		),
		'Achat' => array(
			'className' => 'User',
			'foreignKey' => 'achat_id',
		),
	);
}
