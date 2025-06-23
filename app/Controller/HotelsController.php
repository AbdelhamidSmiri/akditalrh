<?php
App::uses('AppController', 'Controller');
/**
 * Hotels Controller
 *
 * @property Hotel $Hotel
 * @property PaginatorComponent $Paginator
 */
class HotelsController extends AppController {


	public function index() {
		$this->Hotel->recursive = 0;
		$this->set('hotels', $this->Hotel->find("all"));
	}

	public function view($id = null) {
		if (!$this->Hotel->exists($id)) {
			throw new NotFoundException(__('Invalid hotel'));
		}
		$options = array('conditions' => array('Hotel.' . $this->Hotel->primaryKey => $id));
		$this->set('hotel', $this->Hotel->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) 
		{
			
			$this->request->data['Hotel']['images'] = $this->uploadFile('hotels', $this->request->data['Hotel']['images']);
			
			$this->Hotel->create();
			if ($this->Hotel->save($this->request->data)) {
				$this->Session->setFlash(__('The hotel has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The hotel could not be saved. Please, try again.'));
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
		if (!$this->Hotel->exists($id)) {
			throw new NotFoundException(__('Invalid hotel'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Hotel->save($this->request->data)) {
				$this->Session->setFlash(__('The hotel has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The hotel could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Hotel.' . $this->Hotel->primaryKey => $id));
			$this->request->data = $this->Hotel->find('first', $options);
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
		if (!$this->Hotel->exists($id)) {
			throw new NotFoundException(__('Invalid hotel'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Hotel->delete($id)) {
			$this->Session->setFlash(__('The hotel has been deleted.'));
		} else {
			$this->Session->setFlash(__('The hotel could not be deleted. Please, try again.'));
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
