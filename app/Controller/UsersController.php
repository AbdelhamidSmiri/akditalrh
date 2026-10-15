<?php
App::uses('AppController', 'Controller');
App::uses('AppEmail', 'Lib');
App::uses('MigratingPasswordHasher', 'Controller/Component/Auth');
App::uses('LoginThrottle', 'Lib');
App::uses('AuditLog', 'Lib');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('login');
	}

	public function isAuthorized($user)
	{
		$role = AuthComponent::user('Role.role');

		// Self-service / session actions
		if (in_array($this->action, array(
			'view', 'login', 'logout', 'dashboard',
			'mfa_verify', 'mfa_setup', 'mfa_enable', 'mfa_disable',
		), true)) {
			return true;
		}

		// Admin only for user management
		if ($role === 'Admin') {
			return true;
		}

		return false;
	}


	function dashboard($user_id = 0)
	{
		$title_for_layout = "Dashboard";
		$pageSubtitle = "Gérez vos réservations, vos demandes et suivez vos informations en toute simplicité.";

		// Check if user is Admin
		$isAdmin = (AuthComponent::user('Role.role') === 'Admin');

		// Set conditions based on role
		$volConditions = [];
		$hotelConditions = [];

		if (!$isAdmin) {
			// Non-admin users: filter by their user ID
			$user_id = AuthComponent::user('id');
			$volConditions = ["Volreservation.user_id" => $user_id];
			$hotelConditions = ["Reservation.user_id" => $user_id];
		} else {
			// Admin users: get all data (no user_id filter)
			// If a specific user_id is provided in URL, filter by that user
			if ($user_id > 0) {
				$volConditions = ["Volreservation.user_id" => $user_id];
				$hotelConditions = ["Reservation.user_id" => $user_id];
			}
			// Otherwise, leave conditions empty to get all records
		}

		// Fetch data with appropriate conditions
		$vols = $this->User->Volreservation->find("all", ["conditions" => $volConditions]);
		$hotels = $this->User->Reservation->find("all", ["conditions" => $hotelConditions]);

		$data = [];
		$statistique = [
			'en_cours' => 0,
			'vols' => 0,
			'hotels' => 0,
		];

		foreach ($vols as $vol) {
			if ($vol['Volreservation']['etat'] == 'Validé') {
				$statistique['vols']++;
			}
			if ($vol['Volreservation']['etat'] == 'En cours') {
				$statistique['en_cours']++;
			}

			$data[] = [
				"category" => "Voyage",
				"type" => "Billet avion",
				"detail" => $vol['Volreservation']['depart'] . " -> " . $vol['Volreservation']['destination'],
				"dates" => $vol['Volreservation']['date_aller'] . " -> " . $vol['Volreservation']['date_retour'],
				"status" => $vol['Volreservation']['etat'],
				"controller" => "volreservations",
				"id" => $vol['Volreservation']['id'],
				"created" => $vol['Volreservation']['created'],
				// Add user info for admin view
				"user_id" => $vol['Volreservation']['user_id']
			];
		}

		$this->loadModel("Hotel");
		$allhotels = $this->Hotel->find("list");
		$this->Hotel->Chambre->recursive = -1;

		foreach ($hotels as $hotel) {
			// Check if chambre_id exists and is not empty
			if (empty($hotel['Reservation']['chambre_id'])) {
				continue; // Skip this iteration if no chambre_id
			}

			$chambre = $this->Hotel->Chambre->findById($hotel['Reservation']['chambre_id']);

			// Check if chambre was found and has the expected structure
			if (empty($chambre) || !isset($chambre['Chambre']['hotel_id'])) {
				continue; // Skip this iteration if chambre not found or malformed
			}

			$hotel_id = $chambre['Chambre']['hotel_id'];

			// Check if hotel exists in the list
			if (!isset($allhotels[$hotel_id])) {
				continue; // Skip if hotel not found in the list
			}

			$chambre_detail = $allhotels[$hotel_id];

			if ($hotel['Reservation']['etat'] == 'acceptée') {
				$statistique['hotels']++;
			}
			if ($hotel['Reservation']['etat'] == 'En cours') {
				$statistique['en_cours']++;
			}

			$data[] = [
				"category" => "Hébergement",
				"type" => "Hôtel",
				"detail" => $chambre_detail,
				"dates" => $hotel['Reservation']['checkin'] . " -> " . $hotel['Reservation']['checkout'],
				"status" => $hotel['Reservation']['etat'],
				"controller" => "reservations",
				"id" => $hotel['Reservation']['id'],
				"created" => $hotel['Reservation']['created'],
				// Add user info for admin view
				"user_id" => $hotel['Reservation']['user_id']
			];
		}

		// Sort by created date in descending order
		usort($data, function ($a, $b) {
			return strtotime($b['created']) - strtotime($a['created']);
		});

		// Pass admin status to view for conditional display
		$this->set(compact("data", "statistique", "pageSubtitle", "title_for_layout", "isAdmin"));

		// Set the title for the layout
		$this->set('title_for_layout', $title_for_layout);
		$this->set('pageSubtitle', $pageSubtitle);

		// Render the dashboard view
		$this->render('dashboard');
	}

	public function index()
	{
		$title_for_layout = "Gestion des utilisateurs";
		$pageSubtitle = "Vue d’ensemble des comptes et de leurs informations essentielles.";

		$this->User->recursive = 0;
		$this->set('users', $this->User->find("all"));
		$this->set(compact("pageSubtitle", 'title_for_layout'));
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null)
	{
		// If user is not logged in → go to login
		if (!AuthComponent::user()) {
			return $this->redirect(array('action' => 'login'));
		}

		// If no ID or current user is not Admin → show own profile
		if ($id === null || AuthComponent::user('Role.role') !== 'Admin') {
			$id = AuthComponent::user('id');
		}

		// Check if user exists
		$options = array('conditions' => array('User.id' => $id));
		$user = $this->User->find('first', $options);

		if (empty($user)) {
			throw new NotFoundException(__('User not found'));
		}

		$title_for_layout = "Détails de l’utilisateur";
		$pageSubtitle = " ";

		$mfaRequired = false;
		if (!empty($user['Role']['role'])) {
			$mfaRequired = in_array($user['Role']['role'], $this->mfaRequiredRoles(), true);
		}
		$this->set(compact('user', 'pageSubtitle', 'title_for_layout', 'mfaRequired'));
	}


	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		$title_for_layout = "Ajouter un utilisateur";
		$pageSubtitle = " ";

		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Flash->success('L\'utilisateur a été enregistré avec succès.');
				return $this->redirect(array('action' => 'index'));
			} else {
				$details = array();
				if (!empty($this->User->validationErrors)) {
					foreach ($this->User->validationErrors as $fieldErrors) {
						foreach ((array)$fieldErrors as $msg) {
							$details[] = $msg;
						}
					}
				}
				$message = 'L\'utilisateur n\'a pas pu être enregistré.';
				if ($details) {
					$message .= ' ' . implode(' ', array_unique($details));
				} else {
					$message .= ' Veuillez réessayer.';
				}
				$this->Flash->error($message);
			}
		}
		$roles = $this->User->Role->find('list');
		$this->set(compact('roles'));
		$this->set(compact("pageSubtitle", 'title_for_layout'));
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null)
	{
		$title_for_layout = "Modifier un utilisateur";
		$pageSubtitle = " ";

		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Flash->success('L\'utilisateur a été modifié avec succès.');
				return $this->redirect(array('action' => 'index'));
			} else {
				$details = array();
				if (!empty($this->User->validationErrors)) {
					foreach ($this->User->validationErrors as $fieldErrors) {
						foreach ((array)$fieldErrors as $msg) {
							$details[] = $msg;
						}
					}
				}
				$message = 'L\'utilisateur n\'a pas pu être modifié.';
				if ($details) {
					$message .= ' ' . implode(' ', array_unique($details));
				} else {
					$message .= ' Veuillez réessayer.';
				}
				$this->Flash->error($message);
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$roles = $this->User->Role->find('list');
		$this->set(compact('roles'));
		$this->set(compact("pageSubtitle", 'title_for_layout'));
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null)
	{
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->allowMethod('post', 'delete');

		$id = (int)$id;
		$db = $this->User->getDataSource();
		$db->begin();
		try {
			// Remove related rows that block FK delete (demandevoitures has no ON DELETE CASCADE)
			$db->query('DELETE FROM demandevoitures WHERE user_id = ' . $id);
			// demandes already cascades on user delete

			if ($this->User->delete($id)) {
				$db->commit();
				$this->Session->setFlash(
					__('L\'utilisateur a été supprimé.'),
					'Flash/success',
					array(),
					'success'
				);
			} else {
				$db->rollback();
				$this->Session->setFlash(
					__('Impossible de supprimer cet utilisateur. Veuillez réessayer.'),
					'Flash/error',
					array(),
					'error'
				);
			}
		} catch (Exception $e) {
			$db->rollback();
			$this->Session->setFlash(
				__('Impossible de supprimer cet utilisateur : des données liées existent encore.'),
				'Flash/error',
				array(),
				'error'
			);
		}
		return $this->redirect(array('action' => 'index'));
	}



	public function login()
	{
		$this->layout = 'login';
		if ($this->Auth->user()) {
			return $this->redirect($this->Auth->loginRedirect);
		}
		if ($this->request->is('post')) {
			$username = isset($this->request->data['User']['username'])
				? $this->request->data['User']['username']
				: '';

			if (LoginThrottle::isBlocked($username)) {
				$seconds = LoginThrottle::remainingLockSeconds($username);
				$minutes = max(1, (int)ceil($seconds / 60));
				$this->Session->setFlash(
					'Trop de tentatives. Réessayez dans environ ' . $minutes . ' minute(s).',
					'Flash/error',
					array(),
					'error'
				);
				return;
			}

			if ($this->Auth->login()) {
				LoginThrottle::clear($username);
				$this->_upgradeLegacyPasswordIfNeeded();

				// Reload auth user fields including MFA flags
				$fresh = $this->User->find('first', array(
					'conditions' => array('User.id' => $this->Auth->user('id')),
					'contain' => false,
					'recursive' => 0,
				));
				if (!empty($fresh['User'])) {
					$userData = $fresh['User'];
					if (!empty($fresh['Role'])) {
						$userData['Role'] = $fresh['Role'];
					}
					$this->Session->write('Auth.User', $userData);
				}

				if (!empty($fresh['User']['totp_enabled'])) {
					$this->Session->delete('Auth.mfa_verified');
					AuditLog::write('login_password_ok_mfa_required');
					return $this->redirect(array('action' => 'mfa_verify'));
				}

				if ($this->isMfaRequiredForCurrentUser()) {
					AuditLog::write('login_password_ok_mfa_enrollment_required');
					$this->Session->setFlash(
						'La double authentification (MFA) est obligatoire. Veuillez l’activer pour continuer.',
						'Flash/error',
						array(),
						'error'
					);
					return $this->redirect(array('action' => 'mfa_setup'));
				}

				$this->Session->write('Auth.mfa_verified', true);
				AuditLog::write('login_success');

				$this->Session->setFlash(
					'Bonjour ' . $this->Auth->user('nom') . ', vous êtes connecté avec succès.',
					'Flash/success',
					array(),
					'success'
				);
				$role = AuthComponent::user('Role.role');
				if ($role == "Admin") {
					$this->Auth->redirectUrl(array('controller' => 'users', 'action' => 'index'));
				} elseif ($role == "Agence") {
					$this->Auth->redirectUrl(array('controller' => 'volreservations', 'action' => 'agence_index'));
				} elseif ($role !== ' Admin' && $role !== 'Agence') {
					$this->Auth->redirectUrl(array('controller' => 'volreservations', 'action' => 'agent_index'));
				}

				return $this->redirect($this->Auth->redirect());
			} else {
				LoginThrottle::hitFailure($username);
				$this->Session->setFlash(
					'E-mail ou mot de passe incorrect. Veuillez réessayer.',
					'Flash/error',
					array(),
					'error'
				);
			}
		}
	}

	/**
	 * Re-hash legacy SHA1 passwords to bcrypt after a successful login.
	 */
	protected function _upgradeLegacyPasswordIfNeeded()
	{
		if (empty($this->request->data['User']['password'])) {
			return;
		}

		$userId = $this->Auth->user('id');
		$user = $this->User->find('first', array(
			'conditions' => array('User.id' => $userId),
			'fields' => array('User.id', 'User.password'),
			'recursive' => -1,
		));

		if (empty($user['User']['password'])) {
			return;
		}

		if (!MigratingPasswordHasher::needsRehash($user['User']['password'])) {
			return;
		}

		$this->User->id = $userId;
		$this->User->saveField('password', $this->request->data['User']['password']);
	}

	public function logout()
	{
		AuditLog::write('logout');
		$this->Session->delete('Auth.mfa_verified');
		$this->Session->delete('Mfa.setup_secret');
		return $this->redirect($this->Auth->logout());
	}

	/**
	 * MFA challenge after password login.
	 */
	public function mfa_verify()
	{
		$this->layout = 'login';
		if (!$this->Auth->user()) {
			return $this->redirect(array('action' => 'login'));
		}
		if (!$this->Auth->user('totp_enabled')) {
			$this->Session->write('Auth.mfa_verified', true);
			return $this->redirect($this->Auth->redirect());
		}
		if ($this->Session->read('Auth.mfa_verified')) {
			return $this->redirect($this->Auth->loginRedirect);
		}

		if ($this->request->is('post')) {
			App::uses('Totp', 'Lib');
			$code = isset($this->request->data['User']['code']) ? $this->request->data['User']['code'] : '';
			$secret = $this->Auth->user('totp_secret');
			if (Totp::verify($secret, $code)) {
				$this->Session->write('Auth.mfa_verified', true);
				AuditLog::write('mfa_success');
				$this->Session->setFlash(
					'Authentification à deux facteurs réussie.',
					'Flash/success',
					array(),
					'success'
				);
				$role = AuthComponent::user('Role.role');
				if ($role === 'Admin') {
					return $this->redirect(array('controller' => 'users', 'action' => 'index'));
				}
				if ($role === 'Agence') {
					return $this->redirect(array('controller' => 'volreservations', 'action' => 'agence_index'));
				}
				return $this->redirect(array('controller' => 'volreservations', 'action' => 'agent_index'));
			}
			AuditLog::write('mfa_failed');
			$this->Session->setFlash(
				'Code MFA invalide. Réessayez.',
				'Flash/error',
				array(),
				'error'
			);
		}
	}

	/**
	 * Start MFA enrollment (shows QR + secret).
	 */
	public function mfa_setup()
	{
		$title_for_layout = 'Activer la double authentification';
		$pageSubtitle = 'Scannez le QR code avec Google Authenticator / Microsoft Authenticator.';

		App::uses('Totp', 'Lib');
		$secret = $this->Session->read('Mfa.setup_secret');
		if (!$secret) {
			$secret = Totp::generateSecret();
			$this->Session->write('Mfa.setup_secret', $secret);
		}

		$username = AuthComponent::user('username');
		$otpauth = Totp::provisioningUri($secret, $username);
		$qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . rawurlencode($otpauth);

		$this->set(compact('secret', 'otpauth', 'qrUrl', 'title_for_layout', 'pageSubtitle'));
	}

	/**
	 * Confirm MFA enrollment with a first TOTP code.
	 */
	public function mfa_enable()
	{
		if (!$this->request->is('post')) {
			return $this->redirect(array('action' => 'mfa_setup'));
		}

		App::uses('Totp', 'Lib');
		$secret = $this->Session->read('Mfa.setup_secret');
		$code = isset($this->request->data['User']['code']) ? $this->request->data['User']['code'] : '';

		if (!$secret || !Totp::verify($secret, $code)) {
			$this->Flash->error('Code invalide. Rescannez le QR et réessayez.');
			return $this->redirect(array('action' => 'mfa_setup'));
		}

		$this->User->id = AuthComponent::user('id');
		$this->User->save(array(
			'totp_secret' => $secret,
			'totp_enabled' => 1,
		), array('validate' => false, 'callbacks' => false));

		$user = $this->Session->read('Auth.User');
		$user['totp_secret'] = $secret;
		$user['totp_enabled'] = 1;
		$this->Session->write('Auth.User', $user);
		$this->Session->write('Auth.mfa_verified', true);
		$this->Session->delete('Mfa.setup_secret');

		AuditLog::write('mfa_enabled');
		$this->Flash->success('Double authentification activée.');
		return $this->redirect(array('action' => 'view'));
	}

	/**
	 * Disable MFA (requires current TOTP code).
	 */
	public function mfa_disable()
	{
		$title_for_layout = 'Désactiver la double authentification';
		$pageSubtitle = 'Saisissez un code MFA actuel pour confirmer.';

		if ($this->isMfaRequiredForCurrentUser()) {
			$this->Flash->error('La MFA est obligatoire pour votre rôle et ne peut pas être désactivée.');
			return $this->redirect(array('action' => 'view'));
		}

		if ($this->request->is('post')) {
			App::uses('Totp', 'Lib');
			$code = isset($this->request->data['User']['code']) ? $this->request->data['User']['code'] : '';
			$secret = AuthComponent::user('totp_secret');
			if (!Totp::verify($secret, $code)) {
				$this->Flash->error('Code MFA invalide.');
			} else {
				$this->User->id = AuthComponent::user('id');
				$this->User->save(array(
					'totp_secret' => null,
					'totp_enabled' => 0,
				), array('validate' => false, 'callbacks' => false));

				$user = $this->Session->read('Auth.User');
				$user['totp_secret'] = null;
				$user['totp_enabled'] = 0;
				$this->Session->write('Auth.User', $user);
				$this->Session->write('Auth.mfa_verified', true);
				$this->Session->delete('Mfa.setup_secret');

				AuditLog::write('mfa_disabled');
				$this->Flash->success('Double authentification désactivée.');
				return $this->redirect(array('action' => 'view'));
			}
		}

		$this->set(compact('title_for_layout', 'pageSubtitle'));
	}
}
