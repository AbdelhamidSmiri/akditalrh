<?php
App::uses('AppController', 'Controller');
/**
 * Beneficiaires Controller
 *
 * @property Beneficiaire $Beneficiaire
 * @property PaginatorComponent $Paginator
 */
class BeneficiairesController extends AppController {


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
	public function index() {
		$this->Beneficiaire->recursive = 0;
		$this->set('beneficiaires', $this->Beneficiaire->find("all"));
	}



	function recherche()
	{
		if ($this->request->is('post')) {
			//debug($this->request->data);exit();
			$date_debut = $this->request->data["Beneficiaire"]["date_debut"];
			$date_fin = $this->request->data["Beneficiaire"]["date_fin"];
			$sexe =  $this->request->data["Beneficiaire"]["sexe"];
			$ville = $this->request->data["Beneficiaire"]["ville"];
			
			// Tous les appartements du sexe et ville demandés
			$appartements = $this->Beneficiaire->Appartement->find('all', array(
				'conditions' => array(
					'sexe' => $sexe,
					'ville' => $ville
				)
			));
			
			$disponibles = array();
			
			// Vérifier chaque appartement
			foreach ($appartements as $app) {
				
				// Compter les occupants pendant cette période
				$occupants = $this->Beneficiaire->find('count', array(
					'conditions' => array(
						'appartement_id' => $app['Appartement']['id'],
						'date_debut <=' => $date_fin,
						'date_fin >=' => $date_debut,
						'etat'!='Annuler'
					)
				));
				
				// Si pas plein, ajouter aux disponibles
				if ($occupants < $app['Appartement']['capacite']) 
				{
					$app["Appartement"]["nb_occupants"]=$occupants;
					$disponibles[] = $app;
				}
			}
			debug($disponibles);exit();
			$this->set('appartements', $disponibles);
		}
		$villes=[];
		$villes = $this->Beneficiaire->Appartement->find('list', array(
			'fields' => array('Appartement.ville', 'Appartement.ville'),
			'group' => array('Appartement.ville'),
			'order' => array('Appartement.ville' => 'ASC'),
			'recursive' => -1
		));
		$this->set('villes', $villes);

		
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Beneficiaire->exists($id)) {
			throw new NotFoundException(__('Invalid beneficiaire'));
		}
		
		$this->set('beneficiaire', $this->Beneficiaire->findById($id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Beneficiaire->create();
			$this->request->data["Beneficiaire"]["user_id"] = $this->Auth->user("id");
			if ($this->Beneficiaire->save($this->request->data)) 
			{
				$this->Beneficiaire->Appartement->recursive=-1;
				$appt=$this->Beneficiaire->Appartement->findById($this->request->data["Beneficiaire"]["appartement_id"]);
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
				return $this->redirect(array("controller"=>"appartements",'action' => 'view', $this->request->data["Beneficiaire"]["appartement_id"]));
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
		
		$villes=[];
		$villes = $this->Beneficiaire->Appartement->find('list', array(
			'fields' => array('Appartement.ville', 'Appartement.ville'),
			'group' => array('Appartement.ville'),
			'order' => array('Appartement.ville' => 'ASC'),
			'recursive' => -1
		));
		// a suuper il faut passer par systeme de recherche abdhamid
		$appartements = $this->Beneficiaire->Appartement->find('list');
		$this->set(compact('sites',"villes", 'appartements'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
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
	public function delete($id = null) {
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
		$this->set('token', $token);
		$token = base64_decode(urldecode($token)) ^ 19051983;
		$this->layout="vide";
		if ($this->request->is('post')) 
		{
			$this->Beneficiaire->id = $token;
			$this->Beneficiaire->saveField("etat", "Valider");
			echo $token;exit();
			$this->Session->setFlash(
				'Votre engagement a été validé avec succès.',
				'Flash/success',
				array(),
				'success'
			);
				
			
			$fin="ok";
			$this->set('fin',$fin);
			echo "abdhamid chouf f view dial conditions wach jayak fin= ok si oui affiche dak message dial figma votre....";
			exit();
		}
		
	}
}
