<?php
App::uses('AppController', 'Controller');

/**
 * Parc Controller — parc automobile
 */
class ParcController extends AppController
{
	public $uses = array('Demandevoiture', 'Referentiel', 'User');

	/**
	 * Liste des requêtes véhicule des collaborateurs
	 */
	public function requetes_collaborateurs()
	{
		$title_for_layout = 'Requêtes collaborateurs';
		$pageSubtitle = 'Consultez et traitez les demandes de véhicules des collaborateurs.';

		$demandes = $this->Demandevoiture->find('all', array(
			'order' => array('Demandevoiture.created' => 'DESC'),
			'recursive' => 0,
		));

		$this->set(compact('demandes', 'title_for_layout', 'pageSubtitle'));
	}

	/**
	 * Détail d'une requête
	 */
	public function view_requete($id = null)
	{
		if (!$this->Demandevoiture->exists($id)) {
			throw new NotFoundException(__('Requête invalide'));
		}

		$title_for_layout = 'Détail de la requête';
		$pageSubtitle = 'Informations complètes sur la demande de véhicule.';

		$demande = $this->Demandevoiture->find('first', array(
			'conditions' => array('Demandevoiture.id' => $id),
			'recursive' => 0,
		));

		$this->set(compact('demande', 'title_for_layout', 'pageSubtitle'));
	}
}
