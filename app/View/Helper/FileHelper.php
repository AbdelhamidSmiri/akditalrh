<?php

App::uses('AppHelper', 'View/Helper');
App::uses('FileStorage', 'Lib');

/**
 * Build URLs for files served through MediaController (not public webroot).
 */
class FileHelper extends AppHelper
{
	public $helpers = array('Html');

	/**
	 * @param string $folder Storage folder name
	 * @param string $filename Stored file name
	 * @param bool $full Absolute URL when true
	 * @return string
	 */
	public function storageUrl($folder, $filename, $full = false)
	{
		$filename = basename((string)$filename);
		if ($filename === '') {
			return '';
		}

		$action = FileStorage::isPublicFolder($folder) ? 'serve' : 'download';
		// /media/* avoids clash with physical webroot/files/
		return $this->Html->url(array(
			'controller' => 'media',
			'action' => $action,
			$folder,
			'?' => array('f' => $filename),
		), $full);
	}
}
