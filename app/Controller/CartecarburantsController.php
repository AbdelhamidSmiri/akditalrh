<?php
App::uses('AppController', 'Controller');
/**
 * Cartecarburants Controller
 *
 * @property Cartecarburant $Cartecarburant
 * @property PaginatorComponent $Paginator
 */
class CartecarburantsController extends AppController {

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
		$this->Cartecarburant->recursive = 1;
		$this->set('cartecarburants', $this->Cartecarburant->find('all'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Cartecarburant->exists($id)) {
			throw new NotFoundException(__('Invalid cartecarburant'));
		}
		$options = array('conditions' => array('Cartecarburant.' . $this->Cartecarburant->primaryKey => $id));
		$this->set('cartecarburant', $this->Cartecarburant->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Cartecarburant->create();
			if ($this->Cartecarburant->save($this->request->data)) {
				$this->Flash->success(__('The cartecarburant has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The cartecarburant could not be saved. Please, try again.'));
			}
		}
		$users = $this->Cartecarburant->User->find('list');
		$sites = $this->Cartecarburant->Site->find('list');
		$this->set(compact('users', 'sites'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Cartecarburant->exists($id)) {
			throw new NotFoundException(__('Invalid cartecarburant'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Cartecarburant->save($this->request->data)) {
				$this->Flash->success(__('The cartecarburant has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The cartecarburant could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Cartecarburant.' . $this->Cartecarburant->primaryKey => $id));
			$this->request->data = $this->Cartecarburant->find('first', $options);
		}
		$users = $this->Cartecarburant->User->find('list');
		$sites = $this->Cartecarburant->Site->find('list');
		$this->set(compact('users', 'sites'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->Cartecarburant->exists($id)) {
			throw new NotFoundException(__('Invalid cartecarburant'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Cartecarburant->delete($id)) {
			$this->Flash->success(__('The cartecarburant has been deleted.'));
		} else {
			$this->Flash->error(__('The cartecarburant could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}



    public function import() {
        require_once APP . 'Controller' . DS . 'Component' . DS . 'simplexlsx.php';

        $fichier_excel = WWW_ROOT . 'files' . DS . 'carte.xlsx';
		$this->loadModel('User');
		$this->loadModel('Site');
        $xlsx = new SimpleXLSX($fichier_excel);

        if (!$xlsx->success()) {
            $this->Session->setFlash(__('Erreur lecture Excel : ' . $xlsx->error()));
            return $this->redirect($this->referer());
        }

        $rows = $xlsx->rows();
        $countInserted = 0;
        $countIgnored = 0;

        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // ignorer l'entête

            $nomUtilisateur = trim($row[4]); // Colonne E : Utilisateur
            $nomSite        = trim($row[1]); // Colonne B : Site

            // Recherche utilisateur
            $user = $this->User->find('first', array(
                'conditions' => array('User.nom' => $nomUtilisateur),
                'fields' => array('User.id'),
                'recursive' => -1
            ));

            if (empty($user)) {
                $countIgnored++;
                continue; // pas d'utilisateur => ignorer
            }

            // Recherche site
            $site = $this->Site->find('first', array(
                'conditions' => array('Site.site' => $nomSite),
                'fields' => array('Site.id'),
                'recursive' => -1
            ));

            if (empty($site)) {
                $this->Site->create();
                $this->Site->save(array('Site' => array('site' => $nomSite)));
                $siteId = $this->Site->id;
            } else {
                $siteId = $site['Site']['id'];
            }

            // Préparer data
            $data = array(
                'Cartecarburant' => array(
                    'user_id'       => $user['User']['id'],
                    'site_id'       => $siteId,
                    'prestataire'   => trim($row[0]), // Col A
                    'identifiant'   => trim($row[2]), // Col C
                    'carte_labelle' => trim($row[3]), // Col D
                    'utilisateur'   => $nomUtilisateur,
                    'nv_plafond'    => trim($row[5]), // Col F
                    'type_mt'       => trim($row[6]), // Col G
                    'status'        => trim($row[7])  // Col H
                )
            );

            $this->Cartecarburant->create();
            $this->Cartecarburant->save($data);
            $countInserted++;
        }

        $this->Session->setFlash(__("Import terminé : $countInserted insérés, $countIgnored ignorés."));
        return $this->redirect(array('action' => 'index'));
    }

}
