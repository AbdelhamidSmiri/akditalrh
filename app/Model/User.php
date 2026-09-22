<?php
App::uses('AppModel', 'Model');
App::uses('MigratingPasswordHasher', 'Controller/Component/Auth');

/**
 * User Model
 *
 * @property Role $Role
 * @property Reservation $Reservation
 * @property Volreservation $Volreservation
 */
class User extends AppModel
{
	public $displayField = 'nom';

	public $validate = array(
		'username' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Le nom d\'utilisateur est requis.',
			),
		),
		'password' => array(
			'notBlankOnCreate' => array(
				'rule' => 'notBlank',
				'message' => 'Le mot de passe est requis.',
				'on' => 'create',
			),
			'strength' => array(
				'rule' => array('validatePasswordStrength'),
				'message' => 'Le mot de passe doit contenir au moins 8 caractères, une lettre et un chiffre.',
				'allowEmpty' => true,
			),
			'notCommon' => array(
				'rule' => array('validatePasswordNotObvious'),
				'message' => 'Ce mot de passe est trop courant ou trop proche du nom d\'utilisateur.',
				'allowEmpty' => true,
			),
		),
	);

	public function beforeValidate($options = array())
	{
		// On edit, empty password means "keep current password"
		if (isset($this->data[$this->alias]['password']) && $this->data[$this->alias]['password'] === '') {
			unset($this->data[$this->alias]['password']);
		}
		return true;
	}

	public function beforeSave($options = array())
	{
		if (!empty($this->data[$this->alias]['password'])) {
			$hasher = new MigratingPasswordHasher();
			$this->data[$this->alias]['password'] = $hasher->hash(
				$this->data[$this->alias]['password']
			);
		}
		return true;
	}

	public function validatePasswordStrength($check)
	{
		$password = array_values($check);
		$password = isset($password[0]) ? $password[0] : '';
		if ($password === '' || $password === null) {
			return true;
		}
		if (strlen($password) < 8) {
			return false;
		}
		if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
			return false;
		}
		return true;
	}

	public function validatePasswordNotObvious($check)
	{
		$password = array_values($check);
		$password = isset($password[0]) ? (string)$password[0] : '';
		if ($password === '') {
			return true;
		}

		$lower = strtolower($password);
		$common = array(
			'password', 'password1', 'passw0rd', 'motdepasse', 'azerty123', 'qwerty123',
			'123456789', 'akdital123', 'admin123', 'welcome1', 'iloveyou1', 'abcd1234',
		);
		if (in_array($lower, $common, true)) {
			return false;
		}

		$username = isset($this->data[$this->alias]['username']) ? strtolower((string)$this->data[$this->alias]['username']) : '';
		if ($username !== '') {
			$localPart = strtok($username, '@');
			if (strlen($localPart) >= 4 && strpos($lower, $localPart) !== false) {
				return false;
			}
		}

		return true;
	}

	public $belongsTo = array(
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id',
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

	public $hasMany = array(
		'Reservation' => array(
			'className' => 'Reservation',
			'foreignKey' => 'user_id',
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
			'foreignKey' => 'user_id',
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
