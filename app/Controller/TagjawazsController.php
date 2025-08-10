<?php
App::uses('AppController', 'Controller');
/**
 * Tagjawazs Controller
 *
 * @property Tagjawaz $Tagjawaz
 * @property PaginatorComponent $Paginator
 */
class TagjawazsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Tagjawaz->recursive = 1;
		$this->set('tagjawazs', $this->Tagjawaz->find('all'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Tagjawaz->exists($id)) {
			throw new NotFoundException(__('Invalid tagjawaz'));
		}
		$options = array('conditions' => array('Tagjawaz.' . $this->Tagjawaz->primaryKey => $id));
		$this->set('tagjawaz', $this->Tagjawaz->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Tagjawaz->create();
			if ($this->Tagjawaz->save($this->request->data)) {
				$this->Flash->success(__('The tagjawaz has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The tagjawaz could not be saved. Please, try again.'));
			}
		}
		$users = $this->Tagjawaz->User->find('list');
		$sites = $this->Tagjawaz->Site->find('list');
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
		if (!$this->Tagjawaz->exists($id)) {
			throw new NotFoundException(__('Invalid tagjawaz'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Tagjawaz->save($this->request->data)) {
				$this->Flash->success(__('The tagjawaz has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The tagjawaz could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Tagjawaz.' . $this->Tagjawaz->primaryKey => $id));
			$this->request->data = $this->Tagjawaz->find('first', $options);
		}
		$users = $this->Tagjawaz->User->find('list');
		$sites = $this->Tagjawaz->Site->find('list');
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
		if (!$this->Tagjawaz->exists($id)) {
			throw new NotFoundException(__('Invalid tagjawaz'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Tagjawaz->delete($id)) {
			$this->Flash->success(__('The tagjawaz has been deleted.'));
		} else {
			$this->Flash->error(__('The tagjawaz could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}




    public function import() {
        require_once APP . 'Controller' . DS . 'Component' . DS . 'simplexlsx.php';
		$this->loadModel('User');
		$this->loadModel('Site');

        $fichier_excel = WWW_ROOT . 'files' . DS . 'jawaz.xlsx';
        $xlsx = new SimpleXLSX($fichier_excel);

        if (!$xlsx->success()) {
            $this->Session->setFlash(__('Erreur lecture Excel : ' . $xlsx->error()));
            return $this->redirect($this->referer());
        }

        $rows = $xlsx->rows();
        $countInserted = 0;
        $countIgnored = 0;

        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // Ignorer entête

            $nomSite       = trim($row[0]); // Col A : Site
            $nomUtilisateur = trim($row[1]); // Col B : Utilisateur
            $tagJawaz      = trim($row[2]); // Col C : TAG JAWAZ

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

            // Éviter doublon par tag_jawaz
            $exists = $this->Tagjawaz->find('first', array(
                'conditions' => array('Tagjawaz.ref_jawaz' => $tagJawaz),
                'recursive' => -1
            ));

            if (!empty($exists)) {
                $countIgnored++;
                continue;
            }

            // Sauvegarde
            $data = array(
                'Tagjawaz' => array(
                    'user_id'    => $user['User']['id'],
                    'site_id'    => $siteId,
                    'ref_jawaz'  => $tagJawaz
                )
            );

            $this->Tagjawaz->create();
            $this->Tagjawaz->save($data);
            $countInserted++;
        }

        $this->Session->setFlash(__("Import terminé : $countInserted insérés, $countIgnored ignorés."));
        return $this->redirect(array('action' => 'index'));
    }


}
