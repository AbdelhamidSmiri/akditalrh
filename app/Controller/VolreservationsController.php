<?php
App::uses('AppController', 'Controller');
/**
 * Volreservations Controller
 *
 * @property Volreservation $Volreservation
 * @property PaginatorComponent $Paginator
 */
class VolreservationsController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->Volreservation->recursive = 0;
		$this->set('volreservations', $this->Volreservation->find("all"));
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
	public function add()
	{
		if ($this->request->is('post')) 
		{
			$this->Volreservation->create();
			$this->Volreservation->data["Volreservation"]["user_id"] = AuthComponent::user("id");
			debug($this->request->data);
			$this->request->data['Volreservation']['ordre_mission'] = $this->uploadFile('volreservations', $this->request->data['Volreservation']['ordre_mission']);
			$this->request->data['Volreservation']['cin'] = $this->uploadFile('volreservations', $this->request->data['Volreservation']['cin']);
			$this->request->data['Volreservation']['passport'] = $this->uploadFile('volreservations', $this->request->data['Volreservation']['passport']);
			
			if ($this->Volreservation->save($this->request->data)) {
				$this->Session->setFlash(__('The volreservation has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The volreservation could not be saved. Please, try again.'));
			}
		}
		$sites = $this->Volreservation->Site->find('list');
		$this->set(compact('sites'));
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
		if (!$this->Volreservation->exists($id)) {
			throw new NotFoundException(__('Invalid volreservation'));
		}
		if ($this->request->is(array('post', 'put'))) {
			
			
			if ($this->Volreservation->save($this->request->data)) {
				$this->Session->setFlash(__('The volreservation has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The volreservation could not be saved. Please, try again.'));
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
	public function delete($id = null)
	{
		if (!$this->Volreservation->exists($id)) {
			throw new NotFoundException(__('Invalid volreservation'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Volreservation->delete($id)) {
			$this->Session->setFlash(__('The volreservation has been deleted.'));
		} else {
			$this->Session->setFlash(__('The volreservation could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}



	public function uploadFile($folder, $file)
	{
		if ($file['error'] !== 0)
			return "";

		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		$allowed = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
		if (!in_array(strtolower($ext), $allowed))
			return false;

		$name = uniqid() . '.' . $ext;
		$dir = WWW_ROOT . 'files' . DS . $folder . DS;
		if (!file_exists($dir))
			mkdir($dir, 0755, true);

		return move_uploaded_file($file['tmp_name'], $dir . $name) ? $name : false;
	}
}
