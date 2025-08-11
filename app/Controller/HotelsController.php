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

			if (!empty($this->request->data['Hotel']['ville_autre'])) {
				$villeName = strtoupper(trim($this->request->data['Hotel']['ville_autre']));

				// Check if ville already exists
				$existingVille = $this->Hotel->Ville->find('first', array(
					'conditions' => array('UPPER(Ville.ville)' => $villeName),
					'recursive' => -1
				));

				if ($existingVille) {
					// Use existing ville ID
					$this->request->data['Hotel']['ville_id'] = $existingVille['Ville']['id'];
				} else {
					// Create new ville
					$this->Hotel->Ville->create();
					$this->Hotel->Ville->save(array('ville' => $villeName));
					$this->request->data['Hotel']['ville_id'] = $this->Hotel->Ville->id;
				}
			} else {
				// Use selected ville_id from dropdown
				$this->request->data['Hotel']['ville_id'] = $this->request->data['Hotel']['ville_select'];
			}

			// Sauvegarde
			if ($this->Hotel->save($this->request->data)) {
				$this->Session->setFlash(__('L\'hôtel a été ajouté avec succès.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Erreur lors de l\'ajout du L\'hôtel.'), 'default', array('class' => 'alert alert-danger'));
			}
		}

		$villes_hotel = $this->Hotel->Ville->find('list', array(
			'fields' => array('Ville.id', 'Ville.ville'),
			'group' => array('Ville.ville'),
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
			// Get current hotel data to access existing images
			$currentHotel = $this->Hotel->findById($id);
			$existingImages = !empty($currentHotel['Hotel']['images']) ? $currentHotel['Hotel']['images'] : '';

			// Handle deleted images
			if (!empty($this->request->data['Hotel']['deleted_images'])) {
				// Fix: Ensure deleted_images is a string before exploding
				$deletedImagesString = $this->request->data['Hotel']['deleted_images'];
				if (is_array($deletedImagesString)) {
					// If it's already an array, implode it first
					$deletedImagesString = implode(',', $deletedImagesString);
				}

				$deletedImages = explode(',', $deletedImagesString);
				$this->deleteImages($deletedImages);

				// Remove deleted images from existing images list
				if (!empty($existingImages) && is_string($existingImages)) {
					$existingImagesArray = explode(',', $existingImages);
					// Filter out empty values before array_diff
					$existingImagesArray = array_filter($existingImagesArray);
					$deletedImages = array_filter($deletedImages);

					$existingImagesArray = array_diff($existingImagesArray, $deletedImages);
					$existingImages = !empty($existingImagesArray) ? implode(',', $existingImagesArray) : '';
				} else {
					$existingImages = '';
				}
			}

			// Handle new uploaded images
			$newImages = '';
			if (!empty($this->request->data['Hotel']['images']) && is_array($this->request->data['Hotel']['images'])) {
				$uploadedImages = [];
				foreach ($this->request->data['Hotel']['images'] as $file) {
					if (is_array($file) && isset($file['error']) && $file['error'] === 0) {
						$uploadedImage = $this->uploadFile('hotels', $file);
						if ($uploadedImage) {
							$uploadedImages[] = $uploadedImage;
						}
					}
				}
				$newImages = implode(',', $uploadedImages);
			}

			// Combine existing and new images
			$allImages = array_filter(array($existingImages, $newImages), function ($value) {
				return !empty($value);
			});
			$this->request->data['Hotel']['images'] = implode(',', $allImages);

			// Remove the deleted_images field before saving
			unset($this->request->data['Hotel']['deleted_images']);

			if (!empty($this->request->data['Hotel']['ville_autre'])) {
				$villeName = strtoupper(trim($this->request->data['Hotel']['ville_autre']));

				// Check if ville already exists
				$existingVille = $this->Hotel->Ville->find('first', array(
					'conditions' => array('UPPER(Ville.ville)' => $villeName),
					'recursive' => -1
				));

				if ($existingVille) {
					// Use existing ville ID
					$this->request->data['Hotel']['ville_id'] = $existingVille['Ville']['id'];
				} else {
					// Create new ville
					$this->Hotel->Ville->create();
					$this->Hotel->Ville->save(array('ville' => $villeName));
					$this->request->data['Hotel']['ville_id'] = $this->Hotel->Ville->id;
				}
			} else {
				// Use selected ville_id from dropdown
				$this->request->data['Hotel']['ville_id'] = $this->request->data['Hotel']['ville_select'];
			}

			unset($this->request->data['Hotel']['ville_select']);
			unset($this->request->data['Hotel']['ville_autre']);

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

		$villes_hotel = $this->Hotel->Ville->find('list', array(
			'fields' => array('Ville.id', 'Ville.ville'),
			'group' => array('Ville.ville'),
			'recursive' => -1
		));

		$this->set(compact('villes_hotel'));
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



	private function deleteImages($imageNames)
	{
		$dir = WWW_ROOT . 'files' . DS . 'hotels' . DS;

		foreach ($imageNames as $imageName) {
			$imageName = trim($imageName);
			if (!empty($imageName)) {
				$filePath = $dir . $imageName;
				if (file_exists($filePath)) {
					unlink($filePath);
				}
			}
		}
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
