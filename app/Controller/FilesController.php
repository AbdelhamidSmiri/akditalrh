<?php

App::uses('AppController', 'Controller');

/**
 * Legacy alias — redirects /files/* to /media/* (avoids webroot/files collision).
 */
class FilesController extends AppController
{
	public $uses = array();

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('serve', 'download');
		$this->Security->unlockedActions = array('serve', 'download');
	}

	public function isAuthorized($user)
	{
		return true;
	}

	public function serve($folder = null, $filename = null)
	{
		$query = $this->request->query;
		if ($filename && empty($query['f'])) {
			$query['f'] = $filename;
		}
		return $this->redirect(array(
			'controller' => 'media',
			'action' => 'serve',
			$folder,
			'?' => $query,
		));
	}

	public function download($folder = null, $filename = null)
	{
		$query = $this->request->query;
		if ($filename && empty($query['f'])) {
			$query['f'] = $filename;
		}
		return $this->redirect(array(
			'controller' => 'media',
			'action' => 'download',
			$folder,
			'?' => $query,
		));
	}
}
