<?php

/**
 * Private file storage outside the webroot.
 */
class FileStorage
{
	protected static $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif', 'pdf');

	/** Extension => MIME types accepted after content inspection. */
	protected static $allowedMimes = array(
		'jpg' => array('image/jpeg'),
		'jpeg' => array('image/jpeg'),
		'png' => array('image/png'),
		'gif' => array('image/gif'),
		'pdf' => array('application/pdf'),
	);

	const MAX_UPLOAD_BYTES = 10485760; // 10 MB

	/** Folders that may be served without authentication (marketing images). */
	public static $publicFolders = array('hotels', 'chambres', 'appartements');

	public static function root()
	{
		if (!defined('STORAGE')) {
			define('STORAGE', APP . 'storage' . DS);
		}
		return STORAGE . 'files' . DS;
	}

	public static function legacyRoot()
	{
		return WWW_ROOT . 'files' . DS;
	}

	public static function directory($folder)
	{
		$folder = self::sanitizeFolder($folder);
		$dir = self::root() . $folder . DS;
		if (!is_dir($dir)) {
			mkdir($dir, 0750, true);
		}
		return $dir;
	}

	public static function path($folder, $filename)
	{
		$folder = self::sanitizeFolder($folder);
		$filename = self::sanitizeFilename($filename);
		if ($filename === null) {
			return null;
		}

		$primary = self::root() . $folder . DS . $filename;
		if (is_file($primary)) {
			return $primary;
		}

		$legacy = self::legacyRoot() . $folder . DS . $filename;
		if (is_file($legacy)) {
			return $legacy;
		}

		return null;
	}

	public static function storeUploaded($folder, $file)
	{
		if (!isset($file['error']) || (int)$file['error'] !== UPLOAD_ERR_OK) {
			return false;
		}
		if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
			return false;
		}

		if (!empty($file['size']) && (int)$file['size'] > self::MAX_UPLOAD_BYTES) {
			return false;
		}

		$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		if (!in_array($ext, self::$allowedExtensions, true)) {
			return false;
		}

		// Reject names carrying a second, executable-looking extension (foo.php.jpg)
		if (self::hasSuspiciousExtension($file['name'])) {
			return false;
		}

		if (!self::contentMatchesExtension($file['tmp_name'], $ext)) {
			return false;
		}

		$name = bin2hex(random_bytes(16)) . '.' . $ext;
		$target = self::directory($folder) . $name;

		if (!move_uploaded_file($file['tmp_name'], $target)) {
			return false;
		}

		@chmod($target, 0640);
		return $name;
	}

	public static function storeUploadedMany($folder, $files)
	{
		$uploaded = array();

		// Normalize single-file shape from CakePHP forms
		if (isset($files['name']) && !is_array($files['name'])) {
			$name = self::storeUploaded($folder, $files);
			return $name ? array($name) : array();
		}

		if (isset($files['name']) && is_array($files['name'])) {
			$count = count($files['name']);
			for ($i = 0; $i < $count; $i++) {
				$one = array(
					'name' => $files['name'][$i],
					'type' => isset($files['type'][$i]) ? $files['type'][$i] : null,
					'tmp_name' => $files['tmp_name'][$i],
					'error' => $files['error'][$i],
					'size' => isset($files['size'][$i]) ? $files['size'][$i] : 0,
				);
				$saved = self::storeUploaded($folder, $one);
				if ($saved) {
					$uploaded[] = $saved;
				}
			}
			return $uploaded;
		}

		foreach ((array)$files as $file) {
			if (!is_array($file)) {
				continue;
			}
			$saved = self::storeUploaded($folder, $file);
			if ($saved) {
				$uploaded[] = $saved;
			}
		}

		return $uploaded;
	}

	public static function delete($folder, $filename)
	{
		$path = self::path($folder, $filename);
		if ($path && is_file($path)) {
			return @unlink($path);
		}
		return false;
	}

	public static function isPublicFolder($folder)
	{
		return in_array(self::sanitizeFolder($folder), self::$publicFolders, true);
	}

	/**
	 * Detect names such as "invoice.php.pdf" or "shell.phtml.png".
	 */
	public static function hasSuspiciousExtension($originalName)
	{
		$blocked = array('php', 'php3', 'php4', 'php5', 'php7', 'phtml', 'phar', 'exe', 'sh', 'bat', 'cgi', 'pl', 'htaccess');
		$parts = explode('.', strtolower(basename((string)$originalName)));
		array_shift($parts); // filename stem
		array_pop($parts); // real extension, already whitelisted
		foreach ($parts as $middle) {
			if (in_array($middle, $blocked, true)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Inspect real file content so an extension alone cannot smuggle a payload.
	 */
	public static function contentMatchesExtension($tmpPath, $ext)
	{
		if (!isset(self::$allowedMimes[$ext])) {
			return false;
		}

		$detected = null;
		if (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			if ($finfo) {
				$detected = finfo_file($finfo, $tmpPath);
				finfo_close($finfo);
			}
		}

		if ($detected === null && function_exists('mime_content_type')) {
			$detected = mime_content_type($tmpPath);
		}

		// Without a MIME backend, fall back to an image sanity check for images.
		if (!$detected) {
			if ($ext === 'pdf') {
				return true;
			}
			return (bool)@getimagesize($tmpPath);
		}

		return in_array(strtolower($detected), self::$allowedMimes[$ext], true);
	}

	public static function sanitizeFolder($folder)
	{
		$folder = strtolower(preg_replace('/[^a-zA-Z0-9_\-]/', '', (string)$folder));
		return $folder !== '' ? $folder : 'misc';
	}

	public static function sanitizeFilename($filename)
	{
		$filename = basename((string)$filename);
		if ($filename === '' || $filename === '.' || $filename === '..') {
			return null;
		}
		if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $filename)) {
			return null;
		}
		return $filename;
	}
}
