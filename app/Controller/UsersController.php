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

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator');

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
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
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
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
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->Session->write('Auth.User.nom', $this->Auth->user('nom'));
				$this->Session->write('Auth.User.prenom', $this->Auth->user('prenom'));

				$this->loadModel('Role');

				$this->Role->recursive = -2;
				$role = $this->Role->findById($this->Auth->user('role_id'));
				$this->Session->write('Auth.User.role', $role['Role']['role']);
				$this->Session->setFlash(
					'Bonjour ' . $this->Auth->user('username') . ', vous êtes connecté avec succès.',
					'Flash/success',
					array(),
					'success'
				);
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
