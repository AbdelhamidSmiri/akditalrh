<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController
{



	public function isAuthorized($user)
	{
		// Exemples de rôle : agent, agence, admin
		$role = AuthComponent::user('Role.role');
		// Actions autorisées pour "agent"
		if (in_array($this->action, ['view', "login", 'logout', 'forgot_password', "dashboard"])) {
			return true;
		}

		// Admin : accès à tout
		if ($role === 'Admin') {
			return true;
		}
		// Refus par défaut
		return false;
	}


	function dashboard($user_id = 0)
	{
		if ($user_id == 0 || AuthComponent::user('Role.role') !== 'Admin') {
			$user_id = AuthComponent::user('id');
		}
		$vols = $this->User->Volreservation->find("all", ["conditions" => ["Volreservation.user_id" => $user_id]]);
		$hotels = $this->User->Reservation->find("all", ["conditions" => ["Reservation.user_id" => $user_id]]);
		$data = [];
		foreach ($vols as $vol) {
			$data[] = [
				"category" => "Voyage",
				"type" => "Billet avion",
				"detail" => $vol['Volreservation']['depart'] . " -> " . $vol['Volreservation']['destination'],
				"dates" => $vol['Volreservation']['date_aller'] . " -> " . $vol['Volreservation']['date_retour'],
				"status" => $vol['Volreservation']['etat'],
				"controller" => "volreservations",
				"id" => $vol['Volreservation']['id'],
				"created" => $vol['Volreservation']['created']
			];
		}
		$this->loadModel("Hotel");
		$allhotels = $this->Hotel->find("list");
		$this->Hotel->Chambre->recursive = -1;
		foreach ($hotels as $hotel) {
			$chambre = $this->Hotel->Chambre->findById($hotel['Reservation']['chambre_id']);
			$chambre = $allhotels[$chambre['Chambre']['hotel_id']];
			$data[] = [
				"category" => "Hébergement",
				"type" => "Hôtel",
				"detail" => $chambre,
				"dates" => $hotel['Reservation']['checkin'] . " -> " . $hotel['Reservation']['checkout'],
				"status" => $hotel['Reservation']['etat'],
				"controller" => "reservations",
				"id" => $hotel['Reservation']['id'],
				"created" => $hotel['Reservation']['created']
			];
		}
		// Sort by created date in descending order
		usort($data, function ($a, $b) {
			return strtotime($b['created']) - strtotime($a['created']);
		});
		//debug($data);exit();
		$this->set(compact("data"));
	}

	public function index()
	{
		$this->User->recursive = 0;
		$this->set('users', $this->User->find("all"));
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

		$this->set('user', $user);
	}


	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Flash->success(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
			}
		}
		$roles = $this->User->Role->find('list');
		$this->set(compact('roles'));
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
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Flash->success(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$roles = $this->User->Role->find('list');
		$this->set(compact('roles'));
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
		if ($this->User->delete($id)) {
			$this->Flash->success(__('The user has been deleted.'));
		} else {
			$this->Flash->error(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}



	public function login()
	{

		// other layout 
		$this->layout = 'login';
		if ($this->Auth->user()) {
			return $this->redirect($this->Auth->loginRedirect); // or Auth->redirect()
		}
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
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
				} elseif ($role !== ' Admin' && $role !== 'Agence')
					$this->Auth->redirectUrl(array('controller' => 'volreservations', 'action' => 'agent_index'));


				return $this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash(
					'E-mail ou mot de passe incorrect. Veuillez réessayer.',
					'Flash/error',
					array(),
					'error'
				);
			}
		}
	}

	public function logout()
	{
		return $this->redirect($this->Auth->logout());
	}

	/**
	 * forgot_password method
	 *
	 * Allows users to request a password reset.
	 * Sends an email with a reset link if the email exists in the database.
	 */


	public function forgot_password()
	{
		$this->layout = 'login';

		if ($this->request->is('post')) {
			$username = $this->request->data['User']['username'];
			$user = $this->User->findByUsername($username);

			if ($user) {
				// Replace with your actual email field if you have one linked to the username
				$toEmail = 'abde-smr@hotmail.fr'; // fallback email or admin email

				$Email = new CakeEmail('default');
				$Email->to($toEmail) // You must set a valid email address here
					->subject('Réinitialisation du mot de passe')
					->template('reset_password')
					->emailFormat('html')
					->viewVars(array('user' => $user))
					->send();

				$this->Session->setFlash(__('Un email a été envoyé pour réinitialiser votre mot de passe.'));
			} else {
				$this->Session->setFlash(__('Nom d\'utilisateur non trouvé.'));
			}
		}
	}
}
