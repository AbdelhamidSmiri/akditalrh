<?php

App::uses('AppController', 'Controller');
App::uses('FileStorage', 'Lib');
App::uses('AuditLog', 'Lib');

/**
 * Controlled file delivery under /media/* (avoids clash with webroot/files/).
 */
class MediaController extends AppController
{
	public $uses = array();

	protected $_roleFolders = array(
		'Agence' => array('volreservations'),
		'_agent' => array('volreservations', 'reservations'),
	);

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('serve');
		$this->Security->unlockedActions = array('serve', 'download');
	}

	public function isAuthorized($user)
	{
		if ($this->action === 'serve' || $this->action === 'download') {
			return true;
		}
		return false;
	}

	/**
	 * Public media: /media/serve/hotels?f=name.jpg
	 */
	public function serve($folder = null, $filename = null)
	{
		if (!FileStorage::isPublicFolder($folder)) {
			throw new ForbiddenException('This folder is not publicly accessible.');
		}
		$filename = $this->_resolveFilename($filename);
		$this->_send($folder, $filename);
	}

	public function download($folder = null, $filename = null)
	{
		if (FileStorage::isPublicFolder($folder)) {
			return $this->redirect(array(
				'controller' => 'media',
				'action' => 'serve',
				$folder,
				'?' => array('f' => $filename),
			));
		}

		$folder = FileStorage::sanitizeFolder($folder);
		$filename = $this->_resolveFilename($filename);
		if (!$this->_canDownloadFolder($folder)) {
			AuditLog::write('file_download_denied', array('folder' => $folder, 'file' => $filename));
			throw new ForbiddenException('Accès au dossier non autorisé pour votre rôle.');
		}

		AuditLog::write('file_download', array('folder' => $folder, 'file' => $filename));
		$this->_send($folder, $filename);
	}

	protected function _resolveFilename($filename)
	{
		if ((empty($filename) || $filename === 'index') && !empty($this->request->query['f'])) {
			$filename = $this->request->query['f'];
		}
		if (!empty($this->request->params['pass']) && count($this->request->params['pass']) > 1) {
			$parts = $this->request->params['pass'];
			array_shift($parts);
			if (!empty($parts)) {
				$joined = implode('.', $parts);
				if ($filename && strpos($filename, '.') === false && strpos($joined, '.') !== false) {
					$filename = $joined;
				}
			}
		}
		return $filename;
	}

	protected function _canDownloadFolder($folder)
	{
		if ($this->isAdmin()) {
			return true;
		}
		if ($this->isAgence()) {
			return in_array($folder, $this->_roleFolders['Agence'], true);
		}
		return in_array($folder, $this->_roleFolders['_agent'], true);
	}

	protected function _send($folder, $filename)
	{
		$path = FileStorage::path($folder, $filename);
		if ($path === null) {
			throw new NotFoundException('File not found.');
		}

		$safeName = FileStorage::sanitizeFilename(basename($path));
		$mime = $this->_mime($path);
		$isPublic = FileStorage::isPublicFolder($folder);
		$this->autoRender = false;
		$this->response->file($path, array(
			'download' => !$isPublic,
			'name' => $safeName,
		));
		$this->response->type($mime);
		$this->response->header('X-Content-Type-Options', 'nosniff');
		if ($isPublic) {
			$this->response->header('Cache-Control', 'public, max-age=86400');
		} else {
			// Personal documents must never be cached by proxies or shared browsers
			$this->response->header('Cache-Control', 'private, no-store, max-age=0');
			$this->response->header('Pragma', 'no-cache');
		}
		return $this->response;
	}

	protected function _mime($path)
	{
		$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
		$map = array(
			'jpg' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'png' => 'image/png',
			'gif' => 'image/gif',
			'pdf' => 'application/pdf',
		);
		return isset($map[$ext]) ? $map[$ext] : 'application/octet-stream';
	}
}
