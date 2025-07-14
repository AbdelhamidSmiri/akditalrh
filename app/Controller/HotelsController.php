<?php
App::uses('AppController', 'Controller');
/**
 * Hotels Controller
 *
 * @property Hotel $Hotel
 * @property PaginatorComponent $Paginator
 */
class HotelsController extends AppController {


	public function index() 
	{
		$this->Hotel->recursive = 0;
		$this->set('hotels', $this->Hotel->find("all", array('group' => array('Hotel.hotel'))));
	}

	public function view($id = null) 
	{
		if (!$this->Hotel->exists($id)) {
			throw new NotFoundException(__('Invalid hotel'));
		}
		$hotel=$this->Hotel->findById($id);
		$hotels =$this->Hotel->findAllByHotel($hotel["Hotel"]["hotel"]);
		$this->set("hotel",$hotel);
		$this->set("hotels",$hotels);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) 
		{
			$this->request->data['Hotel']['images'] = $this->uploadFile('hotels', $this->request->data['Hotel']['images']);
			foreach ($this->request->data['chambre'] as $chambre) 
			{
				
				$zid=0;
				foreach ($chambre['prix'] as $prix) {
					if(!($prix['date_debut']=="" || $prix['date_fin']=="" || $prix['prix']=="")) {
						$zid=1;
					}
				}

				if ($chambre['nom']=="" || $zid==0) {
					continue; // Skip if the chambre name is empty
				}
				$d=array();
				$d["Hotel"]=$this->request->data['Hotel'];
				$d["Hotel"]["nom"]=$chambre['nom'];
				$this->Hotel->create();
				$this->Hotel->save($d);
				foreach ($chambre['prix'] as $prix) 
				{
					if($prix['date_debut']=="" || $prix['date_fin']=="" || $prix['prix']=="")
						continue;
					$prix['hotel_id'] = $this->Hotel->id; // Set the hotel_id to the newly created hotel
					$this->Hotel->Hotelprice->create();
					$this->Hotel->Hotelprice->save($prix);
				}


			}
			$this->Session->setFlash(__('Hotel a été bien ajouté'));
			return $this->redirect(array('action' => 'index'));
			
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
		if (!$this->Hotel->exists($id)) {
			throw new NotFoundException(__('Invalid hotel'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Hotel->save($this->request->data)) {
				$this->Session->setFlash(__('The hotel has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The hotel could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Hotel.' . $this->Hotel->primaryKey => $id));
			$this->request->data = $this->Hotel->find('first', $options);
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
		if (!$this->Hotel->exists($id)) {
			throw new NotFoundException(__('Invalid hotel'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Hotel->delete($id)) {
			$this->Session->setFlash(__('The hotel has been deleted.'));
		} else {
			$this->Session->setFlash(__('The hotel could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}




	public function uploadFile($folder, $file)
	{
		if ($file['error'] !== 0)
			return "";

		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		$allowed = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
		if (!in_array(strtolower($ext), $allowed))
			return false;

		$name = uniqid() . '.' . $ext;
		$dir = WWW_ROOT . 'files' . DS . $folder . DS;
		if (!file_exists($dir))
			mkdir($dir, 0755, true);

		return move_uploaded_file($file['tmp_name'], $dir . $name) ? $name : false;
	}
}
