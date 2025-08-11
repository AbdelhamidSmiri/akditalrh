<?php
App::uses('AppController', 'Controller');
/**
 * Referentiels Controller
 *
 * @property Referentiel $Referentiel
 * @property PaginatorComponent $Paginator
 */
class ReferentielsController extends AppController {

	public function index() {
		$this->set('referentiels', $this->Referentiel->find('all'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Referentiel->exists($id)) {
			throw new NotFoundException(__('Invalid referentiel'));
		}
		$options = array('conditions' => array('Referentiel.' . $this->Referentiel->primaryKey => $id));
		$this->set('referentiel', $this->Referentiel->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Referentiel->create();
			if ($this->Referentiel->save($this->request->data)) {
				$this->Flash->success(__('The referentiel has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The referentiel could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Referentiel->exists($id)) {
			throw new NotFoundException(__('Invalid referentiel'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Referentiel->save($this->request->data)) {
				$this->Flash->success(__('The referentiel has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The referentiel could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Referentiel.' . $this->Referentiel->primaryKey => $id));
			$this->request->data = $this->Referentiel->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->Referentiel->exists($id)) {
			throw new NotFoundException(__('Invalid referentiel'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Referentiel->delete($id)) {
			$this->Flash->success(__('The referentiel has been deleted.'));
		} else {
			$this->Flash->error(__('The referentiel could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}






    public function import() {
        require_once APP . 'Controller' . DS . 'Component' . DS . 'simplexlsx.php';

        $fichier_excel = WWW_ROOT . 'files' . DS . 'ref.xlsx';
        $xlsx = new SimpleXLSX($fichier_excel);

        if (!$xlsx->success()) {
            $this->Session->setFlash(__('Erreur lecture Excel : ' . $xlsx->error()));
            return $this->redirect($this->referer());
        }

        $rows = $xlsx->rows();
        $countInserted = 0;

        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // Ignorer l'entête

            $data = array(
                'Referentiel' => array(
                    'modele'                  => trim($row[0]),
                    'duree'                   => trim($row[1]),
                    'kilometrage'             => trim($row[2]),
                    'loyer_ht_ald'            => $this->toDecimal($row[3]),
                    'loyer_ttc_ald'           => $this->toDecimal($row[4]),
                    'km_supp_ht_ald'          => $this->toDecimal($row[5]),
                    'nb_pneus_ald'            => (int) $row[6],
                    'cat_remplacement_ald'    => trim($row[7]),
                    'loyer_ht_arval'          => $this->toDecimal($row[8]),
                    'loyer_ttc_arval'         => $this->toDecimal($row[9]),
                    'km_supp_ht_arval'        => $this->toDecimal($row[10]),
                    'nb_pneus_arval'          => (int) $row[11],
                    'cat_remplacement_arval'  => trim($row[12]),
                    'moyenne_offres_ttc'      => $this->toDecimal($row[13]),
                    'valeur_actuelle_moy_ttc' => $this->toDecimal($row[14]),
                    'augmentation_pourcent'   => trim($row[15]),
                    'categorie'               => trim($row[16]),
                )
            );

            $this->Referentiel->create();
            $this->Referentiel->save($data);
            $countInserted++;
        }

        $this->Session->setFlash(__("Import terminé : $countInserted lignes insérées."));
        return $this->redirect(array('action' => 'index'));
    }

    private function toDecimal($val) {
        $val = str_replace(array('.', ' '), '', $val); // enlever séparateurs milliers
        $val = str_replace(',', '.', $val); // convertir virgule en point
        return is_numeric($val) ? (float) $val : null;
    }

}
