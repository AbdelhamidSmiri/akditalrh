<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Outils');

/**
 * Reservations Controller
 *
 * @property Reservation $Reservation
 * @property PaginatorComponent $Paginator
 */
class ReservationsController extends AppController
{

	function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('accepte', 'reject');
	}
	public function isAuthorized($user)
	{
		// Exemples de rôle : agent, agence, admin
		$role = AuthComponent::user('Role.role');


		// Actions autorisées pour "agent"
		if ($role !== ' Admin' && $role !== 'Agence') {
			return in_array($this->action, ['index','agent_index', "add", 'view', 'edit']);
		}

		// Admin : accès à tout
		if ($role === 'admin') {
			return true;
		}
		// Refus par défaut
		return false;
	}


	/**
	 * index method
	 *
	 * @return void
	 */
	public function agent_index()
	{
		$pageSubtitle = "Consulter les réservations hôtelières";
		$title_for_layout = "Gestion des Réservations Hôtelières";
				
		$reservations = $this->Reservation->find("all", array(
			'conditions' => array('Reservation.user_id' => AuthComponent::user("id"))
		));
		$this->loadModel("Hotel");
		$hotels = $this->Hotel->find("list");
		$this->set(compact("hotels", "reservations","pageSubtitle",'title_for_layout'));
	}


	public function index()
	{
		$reservations = $this->Reservation->find("all");
		$this->loadModel("Hotel");
		$hotels = $this->Hotel->find("list");
		$this->set(compact("hotels", "reservations"));
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
		if (!$this->Reservation->exists($id)) {
			throw new NotFoundException(__('Invalid reservation'));
		}
		$this->set('reservation', $this->Reservation->findById($id));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add($ville_id = 1)
	{

		$this->loadModel('Hotel');
		$this->Hotel->recursive = -1;

		if ($this->request->is('post')) {
			$image = $this->request->data['Reservation']['cin'];
			$outils = new OutilsController;
			$uploadedImage = $outils->uploadFile('reservations', $image);
			$this->request->data['Reservation']['cin'] = $uploadedImage;

			$image = $this->request->data['Reservation']['ordre_mission'];
			$outils = new OutilsController;
			$uploadedImage = $outils->uploadFile('reservations', $image);
			$this->request->data['Reservation']['ordre_mission'] = $uploadedImage;

			$this->Reservation->create();
			$this->request->data['Reservation']['user_id'] = $this->Auth->user('id');
			if ($this->Reservation->save($this->request->data)) {
				$this->Reservation->Chambre->recursive = -1;
				$chambre = $this->Reservation->Chambre->findById($this->request->data["Reservation"]["chambre_id"]);
				$hotel = $this->Hotel->findById($chambre["Chambre"]["hotel_id"]);
				$resivation = $this->request->data['Reservation'];
				App::uses('CakeEmail', 'Network/Email');
				$Email = new CakeEmail();
				$Email->template('default', 'hotels_validation');
				$Email->viewVars(array("hotel" => $hotel));
				$Email->viewVars(array("chambre" => $chambre));
				$Email->viewVars(array("id" => $this->Reservation->id));
				$Email->viewVars(array("reservation" => $resivation));
				$Email->emailFormat('html');
				$Email->to("godsneek@hotmail.com"); //$hotel["Hotel"]["mail"]
				$Email->from('no-repley@icoz.ma');
				$Email->subject('[AKDITAL] Demande de séjour – Validation requise');
				// Pièce jointe : CIN
				$Email->attachments(array(
					'CIN.jpg' => array(
						'file' => WWW_ROOT . 'files' . DS . 'reservations' . DS . $this->request->data['Reservation']['cin']
					)
				));
				$Email->send();


				$this->Session->setFlash(
					'La reservation a été ajoutée avec succès.',
					'Flash/success',
					array(),
					'success'
				);
				return $this->redirect(array('action' => 'view', $this->Reservation->id));
			} else {
				$this->Session->setFlash(
					"La reservation n'a pas été ajoutée.",
					'Flash/success',
					array(),
					'success'
				);
			}
		}


		//a supprimer apres matsali rajae f t3mrar dial fichier excel
		$ville = $this->Reservation->Site->Ville->findById($ville_id);
		$ville = $ville["Ville"]["ville"];
		//-------------fin superession

		$hotels = $this->Hotel->find('all', array('conditions' => array('Hotel.ville' => $ville)));
		$ids = 0;
		foreach ($hotels as $hotel) {
			$ids = "$ids," . $hotel['Hotel']['id'];
		}
		$chambres = $this->Reservation->Chambre->find('all', array(
			'conditions' => array("Chambre.hotel_id IN ($ids)"),
			'contain' => array('Hotel', 'Hotelprice') // on exclut Reservation ici
		));
		$data = array();
		$today = date('Y-m-d');
		$this->loadModel("Role");
		$role = $this->Role->findById($this->Auth->user('role_id'));

		foreach ($chambres as $chambre) {
			foreach ($chambre['Hotelprice'] as $prix) {
				if ($today >= $prix['date_debut'] && $today <= $prix['date_fin']) {
					if ($role["Role"]["plafond_hotel"] < $prix['prix']) {
						continue; // on ignore les prix supérieurs au plafond
					}
					$chambre['Chambre']['prix'] = $prix['prix'];
					unset($chambre['Hotelprice']);
					$chambre['Chambre']['hotel'] = $chambre['Hotel']['hotel'];
					$chambre['Chambre']['etoile'] = $chambre['Hotel']['etoile'];
					$chambre['Chambre']['images'] = $chambre['Hotel']['images'];
					$chambre['Chambre']['adresse'] = $chambre['Hotel']['adresse'];
					$chambre['Chambre']['reglement'] = $chambre['Hotel']['reglement'];
					unset($chambre['Hotel']);
					$data[] = $chambre;
					break; // on sort de la boucle dès qu'on trouve le bon prix
				}
			}
		}
		$chambres = $data;
		$chambres = $this->Reservation->Chambre->find('list');
		$sites = $this->Reservation->Site->find('list', ["conditions" => ["Site.ville_id" => $ville_id]]);
		$this->loadModel('Ville');
		$villes = $this->Ville->find('list');
		$this->set(compact('chambres', 'sites', "villes"));
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
		if (!$this->Reservation->exists($id)) {
			throw new NotFoundException(__('Invalid reservation'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Reservation->save($this->request->data)) {
				$this->Flash->success(__('The reservation has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The reservation could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Reservation.' . $this->Reservation->primaryKey => $id));
			$this->request->data = $this->Reservation->find('first', $options);
		}
		$users = $this->Reservation->User->find('list');
		$sites = $this->Reservation->Site->find('list');
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
		if (!$this->Reservation->exists($id)) {
			throw new NotFoundException(__('Invalid reservation'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Reservation->delete($id)) {
			$this->Flash->success(__('The reservation has been deleted.'));
		} else {
			$this->Flash->error(__('The reservation could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}


	public function accepte($encryptedId = null)
	{
		$this->layout="vide";
		$id = base64_decode(urldecode($encryptedId)) ^ 19051983;

		if ($this->request->is('post')) {
			$this->Reservation->id = $id;
			if ($this->Reservation->exists()) {
				$this->Reservation->save(array(
					'etat' => 'acceptée',
					'date_reponse' => date('Y-m-d H:i:s'),
					'confirmation' => $this->request->data['Reservation']['confirmation'],
					'reponse' => $this->request->data['Reservation']['reponse']
				));
			
				$reservation = $this->Reservation->findById($id);
				$this->loadModel('Hotel');
				$this->Hotel->recursive = -1;
				$hotel = $this->Hotel->findById($reservation["Chambre"]["hotel_id"]);
				App::uses('CakeEmail', 'Network/Email');
				$email = new CakeEmail();
				$email->template('default', 'hotel_accepte');
				$email->emailFormat('html');
				$email->to("godsneek@hotmail.com"); // $reservation['User']['email']
				$email->from('no-reply@icoz.ma');
				$email->subject('✅ Votre réservation a été acceptée');

				$email->viewVars(array('reservation' => $reservation, 'hotel' => $hotel));
				$email->send();

				echo "Demande validée avec succès.";
				exit();
			}
		}

		$this->set('id', $encryptedId);
	}

	public function reject($encryptedId = null)
	{
		$this->layout="vide";
		$id = base64_decode(urldecode($encryptedId)) ^ 19051983;

		if ($this->request->is('post')) {
			$this->Reservation->id = $id;
			if ($this->Reservation->exists()) 
			{
				$this->Reservation->save(array(
					'etat' => 'refusée',
					'date_reponse' => date('Y-m-d H:i:s'),
					'reponse' => $this->request->data['Reservation']['reponse']
				));
				$reservation = $this->Reservation->findById($id);
				$this->loadModel('Hotel');
				$this->Hotel->recursive = -1;
				$hotel = $this->Hotel->findById($reservation["Chambre"]["hotel_id"]);

				App::uses('CakeEmail', 'Network/Email');
				$email = new CakeEmail();
				$email->template('default', 'hotels_refus');
				$email->emailFormat('html');
				$email->to("godsneek@hotmail.com"); // $reservation['User']['email']
				$email->from('no-reply@icoz.ma');
				$email->subject('❌ Votre réservation a été refusée');

				$email->viewVars(array('reservation' => $reservation, 'hotel' => $hotel));
				$email->send();

				echo 'Demande refusée avec succès.';
				exit();
			}
		}

		$this->set('id', $encryptedId);
	}
}
