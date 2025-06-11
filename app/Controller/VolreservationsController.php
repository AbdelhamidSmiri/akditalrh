<?php
App::uses('AppController', 'Controller');
/**
 * Volreservations Controller
 *
 * @property Volreservation $Volreservation
 * @property PaginatorComponent $Paginator
 */
class VolreservationsController extends AppController {

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
	public function index() {
		$this->Volreservation->recursive = 0;
		$this->set('volreservations', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Volreservation->exists($id)) {
			throw new NotFoundException(__('Invalid volreservation'));
		}
		$options = array('conditions' => array('Volreservation.' . $this->Volreservation->primaryKey => $id));
		$this->set('volreservation', $this->Volreservation->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Volreservation->create();
			if ($this->Volreservation->save($this->request->data)) {
				$this->Flash->success(__('The volreservation has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The volreservation could not be saved. Please, try again.'));
			}
		}
		$users = $this->Volreservation->User->find('list');
		$sites = $this->Volreservation->Site->find('list');
		$this->set(compact('users', 'sites'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Volreservation->exists($id)) {
			throw new NotFoundException(__('Invalid volreservation'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Volreservation->save($this->request->data)) {
				$this->Flash->success(__('The volreservation has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The volreservation could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Volreservation.' . $this->Volreservation->primaryKey => $id));
			$this->request->data = $this->Volreservation->find('first', $options);
		}
		$users = $this->Volreservation->User->find('list');
		$sites = $this->Volreservation->Site->find('list');
		$this->set(compact('users', 'sites'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->Volreservation->exists($id)) {
			throw new NotFoundException(__('Invalid volreservation'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Volreservation->delete($id)) {
			$this->Flash->success(__('The volreservation has been deleted.'));
		} else {
			$this->Flash->error(__('The volreservation could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
