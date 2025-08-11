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
		$this->Auth->allow('index','accepte', 'reject','fetch_hotels_with_details','fetch_chambres');
	}
	public function isAuthorized($user)
	{
		// Exemples de rôle : agent, agence, admin
		$role = AuthComponent::user('Role.role');

		// Actions autorisées pour "agence"
		// Actions autorisées pour "agent"
		if ($role !== ' Admin' && $role !== 'Agence') {
			return in_array($this->action, ['agent_index', "add", 'view', 'edit']);
		}

		// Admin : accès à tout
		if ($role === 'Admin') {
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
		$this->set(compact("hotels", "reservations", "pageSubtitle", 'title_for_layout'));
	}


	public function index()
	{
		$pageSubtitle = "Consulter les réservations hôtelières";
		$title_for_layout = "Gestion des Réservations Hôtelières";
		$reservations = $this->Reservation->find("all");
		$this->loadModel("Hotel");
		$hotels = $this->Hotel->find("list");
		$this->set(compact("hotels", "reservations", "pageSubtitle", 'title_for_layout'));
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
		$title_for_layout = "Détail de la réservation";
		$pageSubtitle = " Informations complètes sur la demande de réservation";
		if (!$this->Reservation->exists($id)) {
			throw new NotFoundException(__('Invalid reservation'));
		}
		$this->set('reservation', $this->Reservation->findById($id));
		$this->set(compact("pageSubtitle", 'title_for_layout'));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add($ville_id = 1)
	{
		$title_for_layout = "Demande d’hôtel";
		$pageSubtitle = "Soumettez une nouvelle demande de réservation d’hôtel.";

		$this->loadModel('Hotel');
		$this->Hotel->recursive = -1;

		if ($this->request->is('post')) {
			$cinFiles = $this->request->data['Reservation']['cin'];
			$outils = new OutilsController;
			$uploadedCin = $outils->uploadFiles('reservations', $cinFiles);
			$this->request->data['Reservation']['cin'] = json_encode($uploadedCin);

			$ordreFiles = $this->request->data['Reservation']['ordre_mission'];
			$uploadedOrdre = $outils->uploadFiles('reservations', $ordreFiles);
			$this->request->data['Reservation']['ordre_mission'] = json_encode($uploadedOrdre);

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
				$Email->to("eljazouly.abdessamad2003@gmail.com"); //$hotel["Hotel"]["mail"]
				$Email->from('no-repley@icoz.ma');
				$Email->subject('[AKDITAL] Demande de séjour – Validation requise');
				// Pièce jointe : CIN
				$Email->attachments(array(
					'CIN.jpg' => array(
						'file' => WWW_ROOT . 'files' . DS . 'reservations' . DS . json_decode($this->request->data['Reservation']['cin'], true)[0]
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

		$chambres = $this->Reservation->Chambre->find('list');
		$sites = $this->Reservation->Site->find('list', ["conditions" => ["Site.ville_id" => $ville_id]]);
		$this->loadModel('Ville');
		$villes = $this->Ville->find('list');
		$this->set(compact('chambres', 'sites', "villes"));
		$this->set(compact("pageSubtitle", 'title_for_layout'));
	}


	/**
	 * Fetch hotels with full details by ville_id via AJAX
	 */
	public function fetch_hotels_with_details()
	{
		$this->autoRender = false;

		if ($this->request->is('ajax') && $this->request->is('post')) {
			$villeId = $this->request->data['ville_id'];

			if (!empty($villeId)) {
				$this->loadModel('Hotel');
				$this->Hotel->recursive = 0; // Get related data

				$hotels = $this->Hotel->find('all', array(
					'conditions' => array('Hotel.ville_id' => $villeId),
					'contain' => array('Ville'), // Get ville data
					'order' => array('Hotel.hotel' => 'ASC')
				));

				$hotelData = array();
				foreach ($hotels as $hotel) {
					$hotelData[] = array(
						'id' => $hotel['Hotel']['id'],
						'hotel' => $hotel['Hotel']['hotel'],
						'etoile' => $hotel['Hotel']['etoile'],
						'images' => $hotel['Hotel']['images'],
						'mail' => $hotel['Hotel']['mail'],
						'telephone' => $hotel['Hotel']['telephone'],
						'nom_responsable' => $hotel['Hotel']['nom_responsable'],
						'adresse' => $hotel['Hotel']['adresse'],
						'reglement' => $hotel['Hotel']['reglement'],
						'ville' => isset($hotel['Ville']['ville']) ? $hotel['Ville']['ville'] : ''
					);
				}

				echo json_encode(array(
					'success' => true,
					'hotels' => $hotelData
				));
			} else {
				echo json_encode(array(
					'success' => false,
					'message' => 'Ville ID manquant'
				));
			}
		} else {
			echo json_encode(array(
				'success' => false,
				'message' => 'Requête invalide'
			));
		}
	}

	/**
	 * Fetch chambres by hotel_id via AJAX (updated for new format)
	 */
	public function fetch_chambres()
	{
		$this->autoRender = false;

		if ($this->request->is('ajax') && $this->request->is('post')) {
			$hotelId = $this->request->data['hotel_id'];

			if (!empty($hotelId)) {
				$chambres = $this->Reservation->Chambre->find('all', array(
					'conditions' => array("Chambre.hotel_id" => $hotelId),
					'contain' => array('Hotel', 'Hotelprice'),
					'order' => array('Chambre.nom' => 'ASC')
				));

				$chambreData = array();
				$today = date('Y-m-d');
				$this->loadModel("Role");
				$role = $this->Role->findById($this->Auth->user('role_id'));

				foreach ($chambres as $chambre) {
					$prix = null;

					// Find current price
					foreach ($chambre['Hotelprice'] as $price) {
						if ($today >= $price['date_debut'] && $today <= $price['date_fin']) {
							if ($role["Role"]["plafond_hotel"] >= $price['prix']) {
								$prix = $price['prix'];
								break;
							}
						}
					}

					// Only include rooms within budget
					if ($prix !== null) {
						$chambreData[] = array(
							'id' => $chambre['Chambre']['id'],
							'nom' => $chambre['Chambre']['nom'],
							'prix' => $prix,
							'type' => isset($chambre['Chambre']['type']) ? $chambre['Chambre']['type'] : 'Standard',
							'capacite' => isset($chambre['Chambre']['capacite']) ? $chambre['Chambre']['capacite'] : 'N/A',
							'description' => isset($chambre['Chambre']['description']) ? $chambre['Chambre']['description'] : 'Chambre confortable'
						);
					}
				}

				echo json_encode(array(
					'success' => true,
					'chambres' => $chambreData
				));
			} else {
				echo json_encode(array(
					'success' => false,
					'message' => 'Hotel ID manquant'
				));
			}
		} else {
			echo json_encode(array(
				'success' => false,
				'message' => 'Requête invalide'
			));
		}
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
		$this->layout = "vide";
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
				$email->to("eljazouly.abdessamad2003@gmail.com"); // $reservation['User']['email']
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
		$this->layout = "vide";
		$id = base64_decode(urldecode($encryptedId)) ^ 19051983;

		if ($this->request->is('post')) {
			$this->Reservation->id = $id;
			if ($this->Reservation->exists()) {
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
				$email->to("eljazouly.abdessamad2003@gmail.com"); // $reservation['User']['email']
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
