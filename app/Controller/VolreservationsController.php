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
		// Exemples de rôle : agent, agence, admin
		$role = AuthComponent::user('Role.role');
		// Actions autorisées pour "agence"
		if ($role === 'Agence') {
			return in_array($this->action, ['agence_index', 'agence_valider', 'agence_archive', "view", 'agence_valide', 'agence_annuler']);
		}

		// Actions autorisées pour "agent"
		if ($role !== ' Admin' && $role !== 'Agence') {
			return in_array($this->action, ['agent_index', 'agence_index', 'index', "add", 'view', 'edit']);
		}

		// Admin : accès à tout
		if ($role === 'admin') {
			return true;
		}
		// Refus par défaut
		return false;
	}

	function agent_index()
	{
		$title_for_layout = "Gestion des Réservations de Vols";
		$pageSubtitle = "Consultez les détails des réservations de vols";


		$this->set('volreservations', $this->Volreservation->find("all", array(
			'conditions' => array('Volreservation.user_id' => AuthComponent::user("id")),
			'order' => array('Volreservation.created' => 'DESC')
		)));


		$this->set(compact("pageSubtitle", 'title_for_layout'));
	}

	function agence_index()
	{
		$title_for_layout = "Demandes de Vols";
		$pageSubtitle = "Consultez les demandes de vols en cours de traitement";
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

	public function agence_valide($id)
	{
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
	}

	function agence_archive($id)
	{
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
		$this->Volreservation->recursive = 0;
		$this->set('volreservations', $this->Volreservation->find("all"));
	}

	public function view($id = null)
	{
		if (!$this->Volreservation->exists($id)) {
			throw new NotFoundException(__('Invalid volreservation'));
		}
		$options = array('conditions' => array('Volreservation.' . $this->Volreservation->primaryKey => $id));

		$this->set('title_for_layout', 'Réservations d\'hôtel'); // for <h2>
		$this->set('pageSubtitle', 'Consulter les réservations des hôtels'); // for <p>
		$this->set('volreservation', $this->Volreservation->find('first', $options));
	}



	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		if ($this->request->is('post')) {
			$this->Volreservation->create();
			$this->Volreservation->data["Volreservation"]["user_id"] = AuthComponent::user("id");

			$this->request->data['Volreservation']['ordre_mission'] = json_encode($this->uploadFiles('volreservations', $this->request->data['Volreservation']['ordre_mission']));
			$this->request->data['Volreservation']['cin'] = json_encode($this->uploadFiles('volreservations', $this->request->data['Volreservation']['cin']));
			$this->request->data['Volreservation']['passport'] = json_encode($this->uploadFiles('volreservations', $this->request->data['Volreservation']['passport']));

			if ($this->Volreservation->save($this->request->data)) {
				$this->Session->setFlash(
					'La réservation de vol a été enregistrée.',
					'Flash/success',
					array(),
					'success'
				);
				return $this->redirect(array('action' => 'agent_index'));
			} else {
				$this->Session->setFlash(
					'La réservation de vol n’a pas pu être enregistrée. Veuillez réessayer.',
					'Flash/error',
					array(),
					'error'
				);
			}
		}
		$sites = $this->Volreservation->Site->find('list');
		$this->set(compact('sites'));
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
		if (!$this->Volreservation->exists($id)) {
			throw new NotFoundException(__('Invalid volreservation'));
		}
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
		if (!$this->Volreservation->exists($id)) {
			throw new NotFoundException(__('Invalid volreservation'));
		}
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
		$uploadedFiles = [];

		foreach ($files as $file) {
			if (!isset($file['error']) || $file['error'] !== 0) continue;

			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
			$allowed = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
			if (!in_array(strtolower($ext), $allowed)) continue;

			$newName = uniqid() . '.' . $ext;
			$dir = WWW_ROOT . 'files' . DS . $folder . DS;
			if (!file_exists($dir)) mkdir($dir, 0755, true);

			if (move_uploaded_file($file['tmp_name'], $dir . $newName)) {
				$uploadedFiles[] = $newName;
			}
		}

		return $uploadedFiles;
	}
}
