<?php
App::uses('AppController', 'Controller');
/**
 * Hotels Controller
 *
 * @property Hotel $Hotel
 * @property PaginatorComponent $Paginator
 */
class HotelsController extends AppController
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

	public function index()
	{
		$this->Hotel->recursive = 0;
		$this->set('hotels', $this->Hotel->find("all"));
	}

	public function view($id = null)
	{
		if (!$this->Hotel->exists($id)) {
			throw new NotFoundException(__('Invalid hotel'));
		}

		if ($this->request->is('post')) {
			// Upload image
			$this->request->data['Chambre']['images'] = $this->uploadFile('chambres', $this->request->data['Chambre']['images']);

			// Création chambre
			$this->loadModel('Chambre');
			$this->Chambre->create();
			if ($this->Chambre->save($this->request->data)) {
				$chambreId = $this->Chambre->id;

				// Ajout des prix si définis
				if (!empty($this->request->data['Chambre']['prices'])) {
					$this->loadModel('Hotelprice');
					foreach ($this->request->data['Chambre']['prices'] as $price) {
						if (!empty($price['date_debut']) && !empty($price['date_fin']) && isset($price['prix'])) {
							$d = array(
								'Hotelprice' => array(
									'date_debut' => $price['date_debut'],
									'date_fin'   => $price['date_fin'],
									'prix'       => $price['prix'],
									'chambre_id' => $chambreId
								)
							);
							$this->Hotelprice->create();
							$this->Hotelprice->save($d);
						}
					}
				}

				$this->Session->setFlash(
					'La chambre a été ajoutée avec succès.',
					'Flash/success',
					array(),
					'success'
				);
				return $this->redirect($this->referer(true));
			} else {
				$this->Session->setFlash(
					'Erreur lors de l\'ajout de la chambre.',
					'Flash/error',
					array(),
					'error'
				);
			}
		}



		$this->Hotel->recursive = 2;
		$hotel = $this->Hotel->findById($id);
		// Chargement liste des hôtels
		$this->loadModel('Hotel');
		$hotels = $this->Hotel->find('list', array(
			'fields' => array('Hotel.id', 'Hotel.hotel')
		));

		$this->set(compact('hotel', 'hotels'));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		if ($this->request->is('post')) {
			$this->request->data['Hotel']['images'] = $this->uploadFile('hotels', $this->request->data['Hotel']['images']);
			// Création d'une nouvelle entité
			$this->Hotel->create();

			// Sauvegarde
			if ($this->Hotel->save($this->request->data)) {
				$this->Session->setFlash(__('L\'hôtel a été ajouté avec succès.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Erreur lors de l\'ajout du L\'hôtel.'), 'default', array('class' => 'alert alert-danger'));
			}
		}

		$villes_hotel = $this->Hotel->find('list', array(
			'fields' => array('Hotel.ville', 'Hotel.ville'),
			'group' => array('Hotel.ville'),
			'recursive' => -1
		));
	
		$this->set(compact('villes_hotel'));
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
	public function delete($id = null)
	{
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
