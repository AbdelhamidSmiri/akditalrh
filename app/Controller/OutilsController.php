<?php
App::uses('AppController', 'Controller');

class OutilsController extends AppController {

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
