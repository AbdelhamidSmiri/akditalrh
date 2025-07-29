<?php
App::uses('AppController', 'Controller');

class OutilsController extends AppController {

	public function uploadFiles($folder, $files)
	{
		$uploadedFiles = [];

		foreach ($files as $file) {
			if (!isset($file['error']) || $file['error'] !== 0) continue;

			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
			$allowed = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
			if (!in_array(strtolower($ext), $allowed)) continue;

			$newName = uniqid() . '.' . $ext;
			$dir = WWW_ROOT . 'files' . DS . $folder . DS;
			if (!file_exists($dir)) mkdir($dir, 0755, true);

			if (move_uploaded_file($file['tmp_name'], $dir . $newName)) {
				$uploadedFiles[] = $newName;
			}
		}

		return $uploadedFiles;
	}
}
