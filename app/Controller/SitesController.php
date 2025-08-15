<?php
App::uses('AppController', 'Controller');
/**
 * Sites Controller
 *
 * @property Site $Site
 * @property PaginatorComponent $Paginator
 */
class SitesController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
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

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$title_for_layout = "Liste des Sites";
		$pageSubtitle = "Visualisez et gérez les informations des sites par ville, adresse et contact.";
		$this->Site->recursive = 0;
		$this->set('sites', $this->Site->find("all"));
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
		$title_for_layout = "Détails du Site";
		$pageSubtitle = "Visualisez les informations complètes du site sélectionné";
		if (!$this->Site->exists($id)) {
			throw new NotFoundException(__('Invalid site'));
		}
		$options = array('conditions' => array('Site.' . $this->Site->primaryKey => $id));
		$this->set('site', $this->Site->find('first', $options));

		$this->set(compact("pageSubtitle", 'title_for_layout'));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		$title_for_layout = "Ajouter un Nouveau Site";
		$pageSubtitle = "Remplissez le formulaire pour créer un nouveau site";
		if ($this->request->is('post')) {
			$this->Site->create();
			if ($this->Site->save($this->request->data)) {
				$this->Flash->success(__('The site has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The site could not be saved. Please, try again.'));
			}
		}
		$villes = $this->Site->Ville->find('list');
		$this->set(compact('villes'));
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
		$title_for_layout = "Modifier le Site";
		$pageSubtitle = "Mettez à jour les informations du site existant";
		if (!$this->Site->exists($id)) {
			throw new NotFoundException(__('Invalid site'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Site->save($this->request->data)) {
				$this->Flash->success(__('The site has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The site could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Site.' . $this->Site->primaryKey => $id));
			$this->request->data = $this->Site->find('first', $options);
		}
		$villes = $this->Site->Ville->find('list');
		$this->set(compact('villes'));
		$this->set(compact("pageSubtitle", 'title_for_layout'));
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
		if (!$this->Site->exists($id)) {
			throw new NotFoundException(__('Invalid site'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Site->delete($id)) {
			$this->Flash->success(__('The site has been deleted.'));
		} else {
			$this->Flash->error(__('The site could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
