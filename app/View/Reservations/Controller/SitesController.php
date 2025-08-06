<?php
App::uses('AppController', 'Controller');
/**
 * Sites Controller
 *
 * @property Site $Site
 * @property PaginatorComponent $Paginator
 */
class SitesController extends AppController {

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
		$this->Site->recursive = 0;
		$this->set('sites', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Site->exists($id)) {
			throw new NotFoundException(__('Invalid site'));
		}
		$options = array('conditions' => array('Site.' . $this->Site->primaryKey => $id));
		$this->set('site', $this->Site->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Site->create();
			if ($this->Site->save($this->request->data)) {
				$this->Flash->success(__('The site has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The site could not be saved. Please, try again.'));
			}
		}
		$villes = $this->Site->Ville->find('list');
		$this->set(compact('villes'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Site->exists($id)) {
			throw new NotFoundException(__('Invalid site'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Site->save($this->request->data)) {
				$this->Flash->success(__('The site has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The site could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Site.' . $this->Site->primaryKey => $id));
			$this->request->data = $this->Site->find('first', $options);
		}
		$villes = $this->Site->Ville->find('list');
		$this->set(compact('villes'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->Site->exists($id)) {
			throw new NotFoundException(__('Invalid site'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Site->delete($id)) {
			$this->Flash->success(__('The site has been deleted.'));
		} else {
			$this->Flash->error(__('The site could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
