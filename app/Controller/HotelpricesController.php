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







	/**
	 * add method
	 *
	 * @return void
	 */
	public function add($hotel_id)
	{
		if ($this->request->is('post')) 
		{
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
	public function edit($id = null)
	{
		if (!$this->Hotelprice->exists($id)) {
			throw new NotFoundException(__('Invalid hotelprice'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Hotelprice->save($this->request->data)) {
				$this->Session->setFlash(__('The hotelprice has been saved.'));
				$h=$this->Hotelprice->findById($id);
				return $this->redirect(array("controller" => "Hotels", 'action' => 'view', $h['Hotelprice']['hotel_id']));
			} else {
				$this->Session->setFlash(__('The hotelprice could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Hotelprice->findById($id);
		}
		$hotels = $this->Hotelprice->Hotel->find('list');
		$this->set(compact('hotels'));
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
