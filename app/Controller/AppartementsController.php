<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Outils');
/**
 * Appartements Controller
 *
 * @property Appartement $Appartement
 * @property PaginatorComponent $Paginator
 */
class AppartementsController extends AppController {

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
	public function index() {
		$this->Appartement->recursive = 1;
		$this->set('appartements', $this->Appartement->find("all"));
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
		if (!$this->Appartement->exists($id)) {
			throw new NotFoundException(__('Invalid appartement'));
		}
		$this->Appartement->recursive = 1;
		$appartement= $this->Appartement->findById($id);
		$this->loadModel('Site');
		$this->loadModel('User');
		$sites= $this->Site->find('list', array('recursive' => -1));
		$users= $this->User->find('list', array('recursive' => -1));
		$this->set(compact('sites', 'users', 'appartement'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) 
		{
			$images="";
			foreach($this->request->data['image'] as $key => $image) {
				$outils = new OutilsController;
				$uploadedImage = $outils->uploadFile('appartements', $image);
				if ($uploadedImage) {
					$images .= $uploadedImage . ';';
				}
			}
			$images = rtrim($images, ';');
			$this->request->data['Appartement']['images'] = $images;
			$this->Appartement->create();
			if ($this->Appartement->save($this->request->data)) {
				$this->Session->setFlash(
					'Appartement a été ajoutée avec succès.',
					'Flash/success',
					array(),
					'success'
				);
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					"Appartement n'a pas été ajoutée.",
					'Flash/success',
					array(),
					'success'
				);
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
		if (!$this->Appartement->exists($id)) {
			throw new NotFoundException(__('Invalid appartement'));
		}
		
		if ($this->request->is(array('post', 'put'))) {
			// Handle image uploads
			if (!empty($this->request->data['Appartement']['image'][0]['name'])) 
			{
				$appt=$this->Appartement->findById($id);
				$images =$appt['Appartement']['images'];
				foreach($this->request->data['Appartement']['image'] as $key => $image) {
					if (!empty($image['name'])) {
						$outils = new OutilsController;
						$uploadedImage = $outils->uploadFile('appartements', $image);
						if ($uploadedImage) {
							$images .= $uploadedImage . ';';
						}
					}
				}
				$images = rtrim($images, ';');
				$this->request->data['Appartement']['images'] = $images;
			} else {
				// Keep existing images if no new ones uploaded
				unset($this->request->data['Appartement']['images']);
			}

			if ($this->Appartement->save($this->request->data)) {
				$this->Session->setFlash(
					'Appartement a été modifiée avec succès.',
					'Flash/success',
					array(),
					'success'
				);
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					"Appartement n'a  pas été modifiée .",
					'Flash/success',
					array(),
					'success'
				);
				$this->Flash->error(__('The appartement could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Appartement->findById($id);
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
		if (!$this->Appartement->exists($id)) {
			throw new NotFoundException(__('Invalid appartement'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Appartement->delete($id)) {
			$this->Session->setFlash(
					'Appartement a été supprimé avec succès.',
					'Flash/success',
					array(),
					'success'
				);
		} else {
			$this->Session->setFlash(
					"Appartement n'a pas été supprimé.",
					'Flash/success',
					array(),
					'success'
				);
		}
		return $this->redirect(array('action' => 'index'));
	}

/**
 * deleteImage method
 * 
 * @throws NotFoundException
 * @param string $id
 * @param string $imageName
 * @return void
 */
	public function deleteImage($id = null, $num = null) {
		$this->request->allowMethod('post');
		$appt = $this->Appartement->findById($id);
		$images = explode(';', $appt['Appartement']['images']);
		$imageName = isset($images[$num]) ? $images[$num] : null;
		if ($imageName) {
			unset($images[$num]);
			unlink($images[$num]);
			// Re-index and save updated images
			$updatedImages = implode(';', $images);
			$this->Appartement->id = $id;
			$this->Appartement->saveField('images', $updatedImages);
		}
		
		return $this->redirect(array('action' => 'edit', $id));
	}
}
