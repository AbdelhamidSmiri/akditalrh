<?php
App::uses('AppController', 'Controller');
/**
 * Beneficiaires Controller
 *
 * @property Beneficiaire $Beneficiaire
 * @property PaginatorComponent $Paginator
 */
class BeneficiairesController extends AppController
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

	function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('conditions');
	}

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$pageSubtitle = "Consultez, modifiez ou terminez les affectations de logement des collaborateurs.";
		$title_for_layout = "Affectations des logements";
		$this->Beneficiaire->recursive = 0;
		$this->set('beneficiaires', $this->Beneficiaire->find("all"));
		$this->set(compact("pageSubtitle", 'title_for_layout'));
	}



	function recherche()
	{
		try {
			if ($this->request->is('post')) {
				// Handle AJAX requests
				if ($this->request->is('ajax')) {
					$this->autoRender = false; // Disable automatic view rendering

					// Use regular POST data for AJAX requests too
					if (!isset($this->request->data['Beneficiaire'])) {
						throw new Exception('No beneficiaire data received');
					}

					$data = $this->request->data['Beneficiaire'];

					// Validate required fields
					if (empty($data["date_debut"]) || empty($data["date_fin"]) || empty($data["sexe"]) || empty($data["ville_id"])) {
						throw new Exception('Missing required fields');
					}

					$date_debut = $data["date_debut"];
					$date_fin = $data["date_fin"];
					$sexe = $data["sexe"];
					$ville = $data["ville_id"];
				} else {
					// Handle regular form submission
					$date_debut = $this->request->data["Beneficiaire"]["date_debut"];
					$date_fin = $this->request->data["Beneficiaire"]["date_fin"];
					$sexe = $this->request->data["Beneficiaire"]["sexe"];
					$ville = $this->request->data["Beneficiaire"]["ville_id"];
				}

				// Tous les appartements du sexe et ville demandés
				$appartements = $this->Beneficiaire->Appartement->find('all', array(
					'conditions' => array(
						'sexe' => $sexe,
						'ville_id' => $ville
					),
					'contain' => array('Ville') // Include Ville data for the response
				));

				$disponibles = array();

				// Vérifier chaque appartement
				foreach ($appartements as $app) {

					// Compter les occupants pendant cette période
					$occupants = $this->Beneficiaire->find('count', array(
						'conditions' => array(
							'Beneficiaire.appartement_id' => $app['Appartement']['id'],
							'Beneficiaire.date_debut <=' => $date_fin,
							'Beneficiaire.date_fin >=' => $date_debut,
							'Beneficiaire.etat !=' => 'Annuler'
						)
					));

					// Si pas plein, ajouter aux disponibles
					if ($occupants < $app['Appartement']['capacite']) {
						$app["Appartement"]["nb_occupants"] = $occupants;
						$disponibles[] = $app;
					}
				}

				// Return JSON for AJAX requests
				if ($this->request->is('ajax')) {
					$this->response->type('json');
					echo json_encode($disponibles);
					return;
				} else {
					// For regular requests, set data for view
					$this->set('appartements', $disponibles);
				}
			}

			// Only load villes if not an AJAX request
			if (!$this->request->is('ajax')) {
				$this->loadModel("Ville");
				$villes = $this->Ville->find('list');
				$this->set('villes', $villes);
			}
		} catch (Exception $e) {
			if ($this->request->is('ajax')) {
				$this->autoRender = false;
				$this->response->statusCode(500);
				$this->response->type('json');
				echo json_encode(array('error' => $e->getMessage()));
				return;
			} else {
				// For debugging, you can uncomment this line:
				// debug($e->getMessage()); exit();
				throw $e; // Re-throw for regular requests
			}
		}
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
		$title_for_layout = "Affectations des logements";
		$pageSubtitle = " ";

		if (!$this->Beneficiaire->exists($id)) {
			throw new NotFoundException(__('Invalid beneficiaire'));
		}

		$this->set('beneficiaire', $this->Beneficiaire->findById($id));
		$this->set(compact("pageSubtitle", 'title_for_layout'));
	}


	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		$title_for_layout = "Nouvelle demande de logement";
		$pageSubtitle = "Complétez les informations pour loger un collaborateur.";
		if ($this->request->is('post')) {
			$this->Beneficiaire->create();
			$this->request->data["Beneficiaire"]["user_id"] = $this->Auth->user("id");
			if ($this->Beneficiaire->save($this->request->data)) {
				$this->Beneficiaire->Appartement->recursive = -1;
				$appt = $this->Beneficiaire->Appartement->findById($this->request->data["Beneficiaire"]["appartement_id"]);
				App::uses('CakeEmail', 'Network/Email');
				$Email = new CakeEmail();
				$Email->template('default', 'appartements_affectation');
				$Email->viewVars(array("appt" => $appt));
				$Email->viewVars(array("id" => $this->Beneficiaire->id));
				$Email->viewVars(array("nom" => $this->request->data["Beneficiaire"]["nom"]));
				$Email->emailFormat('html');
				$Email->to($this->request->data["Beneficiaire"]["mail"]); //$this->request->data["Beneficiaire"]["mail"]
				$Email->from('no-repley@icoz.ma');
				$Email->subject('Affectation à un logement – Validation requise');
				$Email->send();
				$this->Session->setFlash(
					'La demenade de affectation a été ajoutée avec succès.',
					'Flash/success',
					array(),
					'success'
				);
				return $this->redirect(array("controller" => "appartements", 'action' => 'success', $this->request->data["Beneficiaire"]["appartement_id"]));
			} else {
				$this->Session->setFlash(
					"La demenade de affectation n'a pas été ajoutée.",
					'Flash/success',
					array(),
					'success'
				);
			}
		}
		$sites = $this->Beneficiaire->Site->find('list');
		$this->loadModel("Ville");
		$villes = $this->Ville->find('list');
		// a suuper il faut passer par systeme de recherche abdhamid
		$appartements = $this->Beneficiaire->Appartement->find('list');
		$this->set(compact('sites', "villes", 'appartements', 'pageSubtitle', 'title_for_layout'));
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
		if (!$this->Beneficiaire->exists($id)) {
			throw new NotFoundException(__('Invalid beneficiaire'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Beneficiaire->save($this->request->data)) {
				$this->Flash->success(__('The beneficiaire has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The beneficiaire could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Beneficiaire.' . $this->Beneficiaire->primaryKey => $id));
			$this->request->data = $this->Beneficiaire->find('first', $options);
		}
		$sites = $this->Beneficiaire->Site->find('list');
		$users = $this->Beneficiaire->User->find('list');
		$appartements = $this->Beneficiaire->Appartement->find('list');
		$this->set(compact('sites', 'users', 'appartements'));
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
		if (!$this->Beneficiaire->exists($id)) {
			throw new NotFoundException(__('Invalid beneficiaire'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Beneficiaire->delete($id)) {
			$this->Flash->success(__('The beneficiaire has been deleted.'));
		} else {
			$this->Flash->error(__('The beneficiaire could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}



	function conditions($token)
	{
		$fin = "";
		$this->set('token', $token);
		$token = base64_decode(urldecode($token)) ^ 19051983;
		$this->layout = "vide";
		if ($this->request->is('post')) {
			$this->Beneficiaire->id = $token;
			$this->Beneficiaire->saveField("etat", "Valider");
			// echo $token;
			// exit();
			$this->Session->setFlash(
				'Votre engagement a été validé avec succès.',
				'Flash/success',
				array(),
				'success'
			);


			$fin = "ok";
			
		}
		$this->set('fin', $fin);
	}
}
