<?php
App::uses('AppController', 'Controller');
/**
 * Hotels Controller
 *
 * @property Chambre $Chambre
 * @property PaginatorComponent $Paginator
 */
class ChambresController extends AppController
{


	public function index()
	{
		$this->Chambre->recursive = 1;
		$this->set('chambres', $this->Chambre->find("all", array('group' => array('Chambre.created'))));
	}

	public function view($id = null)
	{
		$this->Chambre->recursive = 1;
		if (!$this->Chambre->exists($id)) {
			throw new NotFoundException(__('Invalid chambre'));
		}
		$chambre = $this->Chambre->findById($id);
		$chambres = $this->Chambre->findAllByNom($chambre["Chambre"]["nom"]);
		$this->set("chambre", $chambre);
		$this->set("chambres", $chambres);
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		if ($this->request->is('post')) {
			// Upload image
			$this->request->data['Chambre']['images'] = $this->uploadFile('chambres', $this->request->data['Chambre']['images']);

			// Création chambre
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
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Erreur lors de l\'ajout de la chambre.'), 'default', array('class' => 'alert alert-danger'));
			}
		}

		// Chargement liste des hôtels
		$this->loadModel('Hotel');
		$hotels = $this->Hotel->find('list', array(
			'fields' => array('Hotel.id', 'Hotel.hotel')
		));
		$this->set('hotels', $hotels);
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

		if ($this->request->is('post') || $this->request->is('put')) {
			$chambre_id = $this->request->data['Chambre']['id'];
			if (!$chambre_id || !$this->Chambre->exists($chambre_id)) {
				throw new NotFoundException(__('Chambre invalide'));
			}

			// Upload image si une nouvelle image est envoyée
			if (!empty($this->request->data['Chambre']['images']['name'])) {
				$this->request->data['Chambre']['images'] = $this->uploadFile('chambres', $this->request->data['Chambre']['images']);
			} else {
				// Conserver l'image existante si aucun nouveau fichier
				unset($this->request->data['Chambre']['images']);
			}

			// Sauvegarde de la chambre
			if ($this->Chambre->save($this->request->data)) {

				$this->Session->setFlash(
					'La chambre a été modifiée avec succès.',
					'Flash/success',
					array(),
					'success'
				);

				return $this->redirect(array('controller' => 'Hotels', 'action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('Erreur lors de la modification de la chambre.'), 'default', array('class' => 'alert alert-danger'));
			}
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
		if (!$this->Chambre->exists($id)) {
			throw new NotFoundException(__('Invalid chambre'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Chambre->delete($id)) {
			$this->Session->setFlash(__('The Chambre has been deleted.'));
		} else {
			$this->Session->setFlash(__('The Chambre could not be deleted. Please, try again.'));
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
