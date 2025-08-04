<?php
App::uses('AppController', 'Controller');
/**
 * Villes Controller
 *
 * @property Ville $Ville
 * @property PaginatorComponent $Paginator
 */
class VillesController extends AppController {

	public function isAuthorized($user)
	{
		// Exemples de rôle : agent, agence, admin
		$role = AuthComponent::user('Role.role');
		// Admin : accès à tout
		if ($role === 'Admin') {
			return true;
		}
		// Refus par défaut
		return false;
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Ville->recursive = 0;
		$this->set('villes', $this->Ville->find("all"));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Ville->exists($id)) {
			throw new NotFoundException(__('Invalid ville'));
		}
		$options = array('conditions' => array('Ville.' . $this->Ville->primaryKey => $id));
		$this->set('ville', $this->Ville->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Ville->create();
			if ($this->Ville->save($this->request->data)) {
				$this->Flash->success(__('The ville has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The ville could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Ville->exists($id)) {
			throw new NotFoundException(__('Invalid ville'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Ville->save($this->request->data)) {
				$this->Flash->success(__('The ville has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The ville could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Ville.' . $this->Ville->primaryKey => $id));
			$this->request->data = $this->Ville->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->Ville->exists($id)) {
			throw new NotFoundException(__('Invalid ville'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Ville->delete($id)) {
			$this->Flash->success(__('The ville has been deleted.'));
		} else {
			$this->Flash->error(__('The ville could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
