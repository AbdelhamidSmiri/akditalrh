<?php
App::uses('AppController', 'Controller');
/**
 * Hotelprices Controller
 *
 * @property Hotelprice $Hotelprice
 * @property PaginatorComponent $Paginator
 */
class HotelpricesController extends AppController
{


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
	 * add method
	 *
	 * @return void
	 */
	public function add($hotel_id)
	{
		if ($this->request->is('post')) {
			$this->Hotelprice->create();
			$this->request->data['Hotelprice']['hotel_id'] = $hotel_id; // Set the hotel_id from the parameter
			if ($this->Hotelprice->save($this->request->data)) {
				$this->Session->setFlash(__('The hotelprice has been saved.'));
				return $this->redirect(array("controller" => "Hotels", 'action' => 'view', $hotel_id));
			} else {
				$this->Session->setFlash(__('The hotelprice could not be saved. Please, try again.'));
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
	// public function edit($id = null)
	// {
	// 	if (!$this->Hotelprice->exists($id)) {
	// 		throw new NotFoundException(__('Invalid hotelprice'));
	// 	}
	// 	if ($this->request->is(array('post', 'put'))) {
	// 		if ($this->Hotelprice->save($this->request->data)) {
	// 			$this->Session->setFlash(__('The hotelprice has been saved.'));
	// 			$h=$this->Hotelprice->findById($id);
	// 			return $this->redirect(array("controller" => "Hotels", 'action' => 'view', $h['Hotelprice']['hotel_id']));
	// 		} else {
	// 			$this->Session->setFlash(__('The hotelprice could not be saved. Please, try again.'));
	// 		}
	// 	} else {
	// 		$this->request->data = $this->Hotelprice->findById($id);
	// 	}
	// 	$hotels = $this->Hotelprice->Hotel->find('list');
	// 	$this->set(compact('hotels'));
	// }



	public function edit($id = null)
	{
		if ($this->request->is('post') || $this->request->is('put')) {
			// debug($this->request->data['Chambre']);exit;
			$this->loadModel('Hotelprice');
			$chambreData = $this->request->data['Chambre'];
			$hasSaved = false;
			$hasDeleted = false;

			// ✅ Step 1: Delete hotel prices if delete_ids is provided
			if (!empty($chambreData['delete_ids'])) {
				$idsToDelete = explode(',', $chambreData['delete_ids']);
				if (!empty($idsToDelete)) {
					$this->Hotelprice->deleteAll(array('Hotelprice.id' => $idsToDelete), false);
					$hasDeleted = true;
				}
			}

			// ✅ Step 2: Save or update prices
			if (!empty($chambreData['prices'])) {
				foreach ($chambreData['prices'] as $price) {
					if (!empty($price['date_debut']) && !empty($price['date_fin']) && isset($price['prix'])) {
						$data = array(
							'Hotelprice' => array(
								'date_debut' => $price['date_debut'],
								'date_fin'   => $price['date_fin'],
								'prix'       => $price['prix'],
								'chambre_id' => $price['chambre_id']
							)
						);

						if (!empty($price['id']) && !empty($price['chambre_id'])) {
							$existing = $this->Hotelprice->find('first', array(
								'conditions' => array(
									'Hotelprice.id' => $price['id'],
									'Hotelprice.chambre_id' => $price['chambre_id']
								),
								'recursive' => -1
							));

							if (!empty($existing)) {
								$data['Hotelprice']['id'] = $price['id'];
								if ($this->Hotelprice->save($data)) {
									$hasSaved = true;
								}
								continue;
							}
						}

						// Create new price
						$this->Hotelprice->create();
						if ($this->Hotelprice->save($data)) {
							$hasSaved = true;
						}
					}
				}
			}

			// ✅ Flash messages
			if ($hasSaved && $hasDeleted) {
				$this->Session->setFlash(
					'Les prix ont été sauvegardés et supprimés avec succès.',
					'Flash/success',
					array(),
					'success'
				);
			} elseif ($hasSaved) {
				$this->Session->setFlash(
					'Les prix de la chambre ont été sauvegardés avec succès.',
					'Flash/success',
					array(),
					'success'
				);
			} elseif ($hasDeleted) {
				$this->Session->setFlash(
					'Les prix de la chambre ont été supprimés avec succès.',
					'Flash/success',
					array(),
					'success'
				);
			} else {
				$this->Session->setFlash(
					'Aucun prix n\'a été sauvegardé.',
					'Flash/error',
					array(),
					'error'
				);
			}

			// ✅ Redirect
			return $this->redirect(array('controller' => 'Hotels', 'action' => 'view', $id));
		}
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
		if (!$this->Hotelprice->exists($id)) {
			throw new NotFoundException(__('Invalid hotelprice'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Hotelprice->delete($id)) {
			$this->Session->setFlash(__('The hotelprice has been deleted.'));
		} else {
			$this->Session->setFlash(__('The hotelprice could not be deleted. Please, try again.'));
		}
		return $this->redirect($this->referer());
	}
}
