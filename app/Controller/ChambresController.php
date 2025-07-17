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

				$this->Session->setFlash(__('La chambre a été ajoutée avec succès.'), 'default', array('class' => 'alert alert-success'));
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
		if (!$id || !$this->Chambre->exists($id)) {
			throw new NotFoundException(__('Chambre invalide'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			// Upload image si une nouvelle image est envoyée
			if (!empty($this->request->data['Chambre']['images']['name'])) {
				$this->request->data['Chambre']['images'] = $this->uploadFile('chambres', $this->request->data['Chambre']['images']);
			} else {
				// Conserver l'image existante si aucun nouveau fichier
				unset($this->request->data['Chambre']['images']);
			}

			// Sauvegarde de la chambre
			if ($this->Chambre->save($this->request->data)) {
				$chambreId = $id;
				$this->loadModel('Hotelprice');

				// Supprimer les anciens prix (si nécessaire, sinon tu peux faire une vraie mise à jour)
				$this->Hotelprice->deleteAll(array('Hotelprice.chambre_id' => $chambreId), false);

				// Ajouter les nouveaux prix
				if (!empty($this->request->data['Chambre']['prices'])) {
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

				$this->Session->setFlash(__('La chambre a été modifiée avec succès.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Erreur lors de la modification de la chambre.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			// Pré-remplir le formulaire
			$this->request->data = $this->Chambre->read(null, $id);
			$this->loadModel('Hotelprice');
			$rawPrices = $this->Hotelprice->find('all', array(
				'conditions' => array('Hotelprice.chambre_id' => $id),
				'fields' => array('Hotelprice.id', 'Hotelprice.date_debut', 'Hotelprice.date_fin', 'Hotelprice.prix'),
				'recursive' => -1
			));

			// Reformater pour qu'on ait : [0 => [date_debut => ..., ...], 1 => [...], ...]
			$prices = array();
			foreach ($rawPrices as $p) {
				$prices[] = $p['Hotelprice'];
			}
			$this->request->data['Chambre']['prices'] = $prices;
		}

		// Liste des hôtels
		$this->loadModel('Hotel');
		$hotels = $this->Hotel->find('list', array(
			'fields' => array('Hotel.id', 'Hotel.hotel')
		));
		$this->set('hotels', $hotels);
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
