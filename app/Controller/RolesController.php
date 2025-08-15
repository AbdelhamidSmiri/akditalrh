<?php
App::uses('AppController', 'Controller');
/**
 * Roles Controller
 *
 * @property Role $Role
 * @property PaginatorComponent $Paginator
 */
class RolesController extends AppController
{


	public function isAuthorized($user)
	{
		// Exemples de rôle : agent, agence, admin
		$role = AuthComponent::user('Role.role');
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
	public function index()
	{
		$title_for_layout = "Gestion des Rôles";
		$pageSubtitle = " ";

		$this->Role->recursive = 0;
		$this->set('roles', $this->Role->find("all"));
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
		$title_for_layout = "Détails du rôle";
		$pageSubtitle = " ";
		if (!$this->Role->exists($id)) {
			throw new NotFoundException(__('Invalid role'));
		}
		$options = array('conditions' => array('Role.' . $this->Role->primaryKey => $id));
		$this->set('role', $this->Role->find('first', $options));
		$this->set(compact("pageSubtitle", 'title_for_layout'));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		$title_for_layout = "Ajouter un rôle";
		$pageSubtitle = " ";
		if ($this->request->is('post')) {
			$this->Role->create();
			if ($this->Role->save($this->request->data)) {
				$this->Flash->success(__('The role has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The role could not be saved. Please, try again.'));
			}
		}
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
		$title_for_layout = "Modifier un rôle";
		$pageSubtitle = " ";
		if (!$this->Role->exists($id)) {
			throw new NotFoundException(__('Invalid role'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Role->save($this->request->data)) {
				$this->Flash->success(__('The role has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The role could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Role.' . $this->Role->primaryKey => $id));
			$this->request->data = $this->Role->find('first', $options);
		}
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
		if (!$this->Role->exists($id)) {
			throw new NotFoundException(__('Invalid role'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Role->delete($id)) {
			$this->Flash->success(__('The role has been deleted.'));
		} else {
			$this->Flash->error(__('The role could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
