<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Outils');
/**
 * Appartements Controller
 *
 * @property Appartement $Appartement
 * @property PaginatorComponent $Paginator
 */
class AppartementsController extends AppController
{

	public function isAuthorized($user)
	{
		// Exemples de rôle : agent, agence, admin
		$role = AuthComponent::user('Role.role');
		// Admin : accès à tout
		if ($role === 'Admin') {
			return true;
		}
		// Refus par défaut
		return false;
	}
	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	
	public function success($id =null)
	{
		
		
	}

	public function index()
	{
		$title_for_layout = "Disponibilités des logements";
		$pageSubtitle = "Consultez la capacité et le taux d’occupation de chaque appartement.";

		$appartements = $this->Appartement->find('all', [
			'contain' => ['Ville', 'Beneficiaire']
		]);

		$stats = [];
		$global = [
			'total_appartements' => 0,
			'total_ocupes' => 0,
			'total_places_dispo' => 0,
		];

		foreach ($appartements as $a) {
			$ville = $a['Ville']['ville'];
			$sexe = $a['Appartement']['sexe'];
			$cap = (int) $a['Appartement']['capacite'];

			$nb_ocupants = 0;
			foreach ($a['Beneficiaire'] as $b) {
				if ($b['etat'] == 'Checkin') {
					$nb_ocupants++;
				}
			}

			$dispo = $cap - $nb_ocupants;

			// Statistiques par ville et sexe
			if (!isset($stats[$ville])) {
				$stats[$ville] = [];
			}
			if (!isset($stats[$ville][$sexe])) {
				$stats[$ville][$sexe] = ['total' => 0, 'ocupes' => 0, 'places_dispo' => 0];
			}

			$stats[$ville][$sexe]['total']++;
			if ($nb_ocupants >= $cap) {
				$stats[$ville][$sexe]['ocupes']++;
			}
			$stats[$ville][$sexe]['places_dispo'] += $dispo;

			// Pour chaque appartement
			$appart_info[] = [
				'id' => $a['Appartement']['id'],
				'nom' => $a['Appartement']['nom'],
				'ville' => $ville,
				'sexe' => $sexe,
				'capacite' => $cap,
				'ocupants' => $nb_ocupants,
				'places_dispo' => $dispo
			];

			$global['total_appartements']++;
			if ($nb_ocupants >= $cap) {
				$global['total_ocupes']++;
			}
			$global['total_places_dispo'] += $dispo;
		}

		$this->set(compact('stats', 'appart_info', 'global'));
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
			$title_for_layout = "Détails de l’appartement";
		$pageSubtitle = "Consultez les informations générales, l’historique et les affectations liées à ce logement.";

		if (!$this->Appartement->exists($id)) {
			throw new NotFoundException(__('Invalid appartement'));
		}
		$this->Appartement->recursive = 1;
		$appartement = $this->Appartement->findById($id);
		$this->loadModel('Site');
		$this->loadModel('User');
		$sites = $this->Site->find('list');
		$users = $this->User->find('list');
		$villes = $this->Appartement->Ville->find('list');
		$this->set(compact('sites', 'users', 'appartement', "villes"));
		$this->set(compact("pageSubtitle", 'title_for_layout'));

	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
			$title_for_layout = "Ajouter un nouvel appartement";
		$pageSubtitle = "Renseignez les détails du logement";

		if ($this->request->is('post')) {

			$images = $this->request->data['Appartement']['images'];
			$outils = new OutilsController;
			$uploadedImage = $outils->uploadFiles('reservations', $images);
			$this->request->data['Appartement']['images'] = json_encode($images);

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
		$villes = $this->Appartement->Ville->find('list');
		$this->set(compact("villes"));
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
		if (!$this->Appartement->exists($id)) {
			throw new NotFoundException(__('Invalid appartement'));
		}

		if ($this->request->is(array('post', 'put'))) {
			// Handle image uploads
			if (!empty($this->request->data['Appartement']['images'][0]['name'])) {
				$appt = $this->Appartement->findById($id);
				$images = $this->request->data['Appartement']['images'];
				$outils = new OutilsController;
				$uploadedImage = $outils->uploadFiles('reservations', $images);
				$this->request->data['Appartement']['images'] = json_encode($images);
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
		$villes = $this->Appartement->Ville->find('list');
		$this->set(compact("villes"));
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
	public function deleteImage($id = null, $num = null)
	{
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
