<?php
App::uses('AppController', 'Controller');
/**
 * Volreservations Controller
 *
 * @property Volreservation $Volreservation
 * @property PaginatorComponent $Paginator
 */
class VolreservationsController extends AppController
{

	public function isAuthorized($user)
	{
		$role = AuthComponent::user('Role.role');

		if ($role === 'Admin') {
			return true;
		}

		// Agence: strict flight-ops surface only (no generic index / CRUD)
		if ($role === 'Agence') {
			return in_array($this->action, array(
				'agence_index',
				'agence_valider',
				'agence_archive',
				'agence_valide',
				'agence_annuler',
				'view',
			), true);
		}

		if ($role !== 'Admin' && $role !== 'Agence') {
			return in_array($this->action, array('agent_index', 'add', 'view', 'edit'), true);
		}

		return false;
	}


	function agent_index()
	{
		$title_for_layout = "Mes demandes de billets d’avion";
		$pageSubtitle = "Suivez le traitement de vos demandes et accédez à vos billets validés.";


		$this->set('volreservations', $this->Volreservation->find("all", array(
			'conditions' => array('Volreservation.user_id' => AuthComponent::user("id")),
			'order' => array('Volreservation.created' => 'DESC')
		)));


		$this->set(compact("pageSubtitle", 'title_for_layout'));
	}

	function agence_index()
	{
		$title_for_layout = "Gestion des demandes de billets d’avion";
		$pageSubtitle = "Consultez et traitez les demandes en cours.";
		$volreservations = $this->Volreservation->find("all", array(
			'conditions' => array('Volreservation.etat' => "En cours"),
			'order' => array('Volreservation.created' => 'DESC') // or any other field you want to sort by
		));

		$this->set('title_for_layout', 'Réservations d\'hôtel'); // for <h2>
		$this->set('pageSubtitle', 'Consulter les réservations des hôtels'); // for <p>
		$this->set(compact('volreservations'));
		$this->set(compact("pageSubtitle", 'title_for_layout'));
	}



	function agence_valider()
	{
		$title_for_layout = "Vols Terminés";
		$pageSubtitle = "Consultez l’historique des vols effectués";
		$this->set('volreservations', $this->Volreservation->find("all", array(
			'conditions' => array('Volreservation.etat' => "Validé")
		)));
		$this->set(compact("pageSubtitle", 'title_for_layout'));
	}
	function agence_annuler()
	{
		$title_for_layout = "Vols Annulés";
		$pageSubtitle = "Consultez les réservations de vols annulées.";
		$this->set('volreservations', $this->Volreservation->find("all", array(
			'conditions' => array('Volreservation.etat' => "Annulé")
		)));
		$this->set(compact("pageSubtitle", 'title_for_layout'));
	}
	public function agence_view($id = null)
	{
		if (!$this->Volreservation->exists($id)) {
			throw new NotFoundException(__('Invalid volreservation'));
		}
		$options = array('conditions' => array('Volreservation.id' => $id));
		$this->set('volreservation', $this->Volreservation->find('first', $options));
	}

	public function agence_valide($token = null)
	{
		$id = $this->decodeOpaqueId('volreservation', $token);
		$title_for_layout = "Valider et émettre le billet";
		$pageSubtitle = "Complétez les informations nécessaires pour valider la demande de billet d’avion.";
		if ($this->request->is('post')) {
			$this->Volreservation->id = $id;
			$this->request->data['Volreservation']['date_reponse'] = date("Y-m-d H:i:s");
			$this->request->data['Volreservation']['etat'] = "Validé";
			$this->request->data['Volreservation']['file_aller'] = json_encode($this->uploadFiles('volreservations', $this->request->data['Volreservation']['file_aller']));
			$this->request->data['Volreservation']['file_retour'] = json_encode($this->uploadFiles('volreservations', $this->request->data['Volreservation']['file_retour']));
			$this->request->data['Volreservation']['documents'] = json_encode($this->uploadFiles('volreservations',  $this->request->data['Volreservation']['documents']));
			$this->Volreservation->save($this->request->data);
			$this->Session->setFlash(
				'La réservation a été validée.',
				'Flash/success',
				array(),
				'success'
			);
			return $this->redirect(array('action' => 'agence_index'));
		}
		$vol = $this->Volreservation->findById($id);
		$this->set('vol', $vol);
		$this->set('opaqueId', SecureId::encode('volreservation', $id));
		$this->set(compact("pageSubtitle", 'title_for_layout'));
	}

	function agence_archive($token = null)
	{
		$id = $this->decodeOpaqueId('volreservation', $token);
		if (!$this->Volreservation->exists($id)) {
			throw new NotFoundException(__('Invalid volreservation'));
		}


		if ($this->request->is(array('post', 'put'))) {
			$this->Volreservation->id = $id;
			$this->request->data['Volreservation']['date_reponse'] = date("Y-m-d H:i:s");
			$this->request->data['Volreservation']['etat'] = "Annulé";
			$this->Volreservation->save($this->request->data);
			$this->Session->setFlash(__('La réservation a été annulée.'));
		}
		return $this->redirect(array('action' => 'agence_index'));
	}




public function index()
{
    $title_for_layout = "Demandes de billets d’avion";
    $pageSubtitle = "Consultez et traitez les demandes reçues de la part des collaborateurs.";

    $this->Volreservation->recursive = 0;

    $volreservations = $this->Volreservation->find('all', [
        'order' => [
            'Volreservation.date_aller' => 'DESC', // newest outbound flight first
            'Volreservation.id' => 'DESC'         // secondary sort for consistent ordering
        ]
    ]);

    $this->set('volreservations', $volreservations);
    $this->set(compact('pageSubtitle', 'title_for_layout'));
}




	public function view($token = null)
	{
		$title_for_layout = "Détails de la demande de billet d’avion";
		$pageSubtitle = "Consultez les informations fournies par le demandeur et procédez à la validation ou au refus
de la demande";
		$id = $this->decodeOpaqueId('volreservation', $token);
		if (!$this->Volreservation->exists($id)) {
			throw new NotFoundException(__('Invalid volreservation'));
		}
		$this->_ensureVolAccess($id);
		$options = array('conditions' => array('Volreservation.' . $this->Volreservation->primaryKey => $id));

		$this->set('volreservation', $this->Volreservation->find('first', $options));
		$this->set(compact("pageSubtitle", 'title_for_layout'));
	}



	/**
	 * add method
	 *demandes de vols

	 * @return void
	 */
	public function add()
	{
		$title_for_layout = "Demandes de billets d'avion";
		$pageSubtitle = "Veuillez compléter ce formulaire et fournir les pièces justificatives requises pour enregistrervotre demande.";

		if ($this->request->is('post')) {
			$this->Volreservation->create();
			$this->Volreservation->data["Volreservation"]["user_id"] = AuthComponent::user("id");

			$this->request->data['Volreservation']['ordre_mission'] = json_encode($this->uploadFiles('volreservations', $this->request->data['Volreservation']['ordre_mission']));
			$this->request->data['Volreservation']['cin'] = json_encode($this->uploadFiles('volreservations', $this->request->data['Volreservation']['cin']));
			$this->request->data['Volreservation']['passport'] = json_encode($this->uploadFiles('volreservations', $this->request->data['Volreservation']['passport']));

			if ($this->Volreservation->save($this->request->data)) {
				$this->Session->setFlash(
					'Votre demande a été enregistrée avec succès.',
					'Flash/success',
					array(),
					'success'
				);
				return $this->redirect(array('action' => 'agent_index'));
			} else {
				$this->Session->setFlash(
					'Votre demande n’a pas pu être enregistrée avec succès. Veuillez réessayer.',
					'Flash/error',
					array(),
					'error'
				);
			}
		}
		$sites = $this->Volreservation->Site->find('list');
		$this->set(compact('sites'));
		$this->set(compact("pageSubtitle", 'title_for_layout'));
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($token = null)
	{
		$id = $this->decodeOpaqueId('volreservation', $token);
		if (!$this->Volreservation->exists($id)) {
			throw new NotFoundException(__('Invalid volreservation'));
		}
		$this->_ensureVolAccess($id, true);
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Volreservation->save($this->request->data)) {
				$this->Session->setFlash(__('The volreservation has been saved.'));
				return $this->redirect(array('action' => 'agent_index'));
			} else {
				$this->Session->setFlash(__('The volreservation could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Volreservation.' . $this->Volreservation->primaryKey => $id));
			$this->request->data = $this->Volreservation->find('first', $options);
		}
		$users = $this->Volreservation->User->find('list');
		$sites = $this->Volreservation->Site->find('list');
		$this->set(compact('users', 'sites'));
		$this->set('opaqueId', SecureId::encode('volreservation', $id));
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($token = null)
	{
		$id = $this->decodeOpaqueId('volreservation', $token);
		if (!$this->Volreservation->exists($id)) {
			throw new NotFoundException(__('Invalid volreservation'));
		}
		$this->_ensureVolAccess($id, true);
		$this->request->allowMethod('post', 'delete');
		if ($this->Volreservation->delete($id)) {
			$this->Session->setFlash(__('The volreservation has been deleted.'));
		} else {
			$this->Session->setFlash(__('The volreservation could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}



	public function uploadFiles($folder, $files)
	{
		App::uses('FileStorage', 'Lib');
		return FileStorage::storeUploadedMany($folder, $files);
	}

	/**
	 * Agents may only access their own records; Admin/Agence keep broader access.
	 */
	protected function _ensureVolAccess($id, $ownerOnlyForAgent = false)
	{
		$role = AuthComponent::user('Role.role');
		if ($role === 'Admin' || $role === 'Agence') {
			return;
		}

		$record = $this->Volreservation->find('first', array(
			'conditions' => array('Volreservation.id' => $id),
			'fields' => array('Volreservation.id', 'Volreservation.user_id'),
			'recursive' => -1,
		));

		if (empty($record) || (int)$record['Volreservation']['user_id'] !== (int)AuthComponent::user('id')) {
			throw new ForbiddenException('Accès non autorisé à cette demande.');
		}
	}
}
