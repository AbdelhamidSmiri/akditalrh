<?php
App::uses('AppController', 'Controller');
App::uses('FileStorage', 'Lib');

class OutilsController extends AppController
{
	public function isAuthorized($user)
	{
		return $this->isAdmin();
	}

	public function uploadFiles($folder, $files)
	{
		return FileStorage::storeUploadedMany($folder, $files);
	}

	public function uploadFile($folder, $file)
	{
		$result = FileStorage::storeUploaded($folder, $file);
		return $result ? $result : false;
	}
}
