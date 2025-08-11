<?php
App::uses('AppController', 'Controller');
/**
 * Voitures Controller
 *
 * @property Voiture $Voiture
 * @property PaginatorComponent $Paginator
 */
class VoituresController extends AppController {

/**
 * Components
 *
 * @var array
 */

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Voiture->recursive = 1;
		$this->set('voitures', $this->Voiture->find("all"));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Voiture->exists($id)) {
			throw new NotFoundException(__('Invalid voiture'));
		}
		$options = array('conditions' => array('Voiture.' . $this->Voiture->primaryKey => $id));
		$this->set('voiture', $this->Voiture->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Voiture->create();
			if ($this->Voiture->save($this->request->data)) {
				$this->Flash->success(__('The voiture has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The voiture could not be saved. Please, try again.'));
			}
		}
		$users = $this->Voiture->User->find('list');
		$this->set(compact('users'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Voiture->exists($id)) {
			throw new NotFoundException(__('Invalid voiture'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Voiture->save($this->request->data)) {
				$this->Flash->success(__('The voiture has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The voiture could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Voiture.' . $this->Voiture->primaryKey => $id));
			$this->request->data = $this->Voiture->find('first', $options);
		}
		$users = $this->Voiture->User->find('list');
		$this->set(compact('users'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->Voiture->exists($id)) {
			throw new NotFoundException(__('Invalid voiture'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Voiture->delete($id)) {
			$this->Flash->success(__('The voiture has been deleted.'));
		} else {
			$this->Flash->error(__('The voiture could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}


	public function calendrier() {
		$voitures = $this->Voiture->find('all', [
			'contain' => ['User', 'User.Site']
		]);

		$this->set(compact('voitures'));
	}

    public function import_voitures() {
        $fichier_excel = WWW_ROOT . 'files/voitures.xlsx'; // mets ton chemin ici
		require_once 'Component/simplexlsx.php';
		$this->loadModel('User');
		$this->loadModel('Site');
        if ($xlsx = SimpleXLSX::parse($fichier_excel)) {
            $rows = $xlsx->rows();

            for ($i = 1; $i < count($rows); $i++) {
                list(
                    $ste_location, $immat, $marque, $modele, $utilisateur, $site,
                    $direction, $poste, $freq_mobilite, $val_ht, $val_ttc, $duree_mois,
                    $km, $cout_km, $nbr_pneus, $service_link, $categorie,
                    $livraison, $fin_contrat, $km_actuel
                ) = $rows[$i];

                // 1. Chercher le site
                $siteData = $this->Site->find('first', [
                    'conditions' => ['Site.site' => trim($site)],
                    'recursive' => -1
                ]);

                if ($siteData) {
                    $site_id = $siteData['Site']['id'];
                } else {
                    $this->Site->create();
                    $this->Site->save([
                        'ville_id' => 1,
                        'site' => trim($site),
                        'created' => date('Y-m-d H:i:s')
                    ]);
                    $site_id = $this->Site->id;
                }

                // 2. Créer l'utilisateur
                $this->User->create();
                $this->User->save([
                    'nom' => trim($utilisateur),
					'role_id' => 2,
                    'poste' => trim($poste),
                    'direction' => trim($direction),
                    'frequence_mobilite' => trim($freq_mobilite),
                    'site_id' => $site_id
                ]);
                $user_id = $this->User->id;

                // 3. Créer la voiture
                $this->Voiture->create();
                $this->Voiture->save([
                    'user_id' => $user_id,
                    'ste_location' => trim($ste_location),
                    'immatriculation' => trim($immat),
                    'marque' => trim($marque),
                    'modele' => trim($modele),
                    'valeur_locative_ht' => $val_ht,
                    'valeur_locative_ttc' => $val_ttc,
                    'duree_mois' => $duree_mois,
                    'km' => $km,
                    'cout_km_suplm_ttc' => $cout_km,
                    'nbr_pneus' => $nbr_pneus ?: 0,
                    'service_link' => trim($service_link),
                    'categorie_vr' => trim($categorie),
                    'livraison' => date('Y-m-d', strtotime($livraison)),
                    'fin_contrat' => date('Y-m-d', strtotime($fin_contrat)),
                    'km_actuel' =>(int) trim($km_actuel)
                ]);
            }

            $this->Session->setFlash('Import terminé avec succès.');
        } else {
            $this->Session->setFlash('Erreur lecture Excel: ' . SimpleXLSX::parseError());
        }

        $this->redirect($this->referer());
    }
}
