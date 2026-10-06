<?php
/**
 * Front controller: every request goes through this file.
 */

/**
 * Boot failures are logged server-side only; visitors get a neutral 503.
 */
function mgr_boot_failure($detail)
{
	error_log('[MGR boot] ' . $detail);
	if (!headers_sent()) {
		http_response_code(503);
		header('Content-Type: text/plain; charset=UTF-8');
	}
	echo 'Service temporairement indisponible.';
	exit(1);
}

/**
 * Use the DS to separate the directories in other defines
 */
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

/**
 * The full path to the directory which holds "app", WITHOUT a trailing DS.
 */
if (!defined('ROOT')) {
	define('ROOT', dirname(dirname(dirname(__FILE__))));
}

/**
 * The actual directory name for the "app".
 */
if (!defined('APP_DIR')) {
	define('APP_DIR', basename(dirname(dirname(__FILE__))));
}

/**
 * Path to the Composer vendors directory (app/Vendor).
 */
if (!defined('VENDORS')) {
	define('VENDORS', ROOT . DS . APP_DIR . DS . 'Vendor' . DS);
}

/**
 * Editing below this line should NOT be necessary.
 * Change at your own risk.
 */
if (!defined('WEBROOT_DIR')) {
	define('WEBROOT_DIR', basename(dirname(__FILE__)));
}
if (!defined('WWW_ROOT')) {
	define('WWW_ROOT', dirname(__FILE__) . DS);
}

// For the built-in server
if (PHP_SAPI === 'cli-server') {
	if ($_SERVER['PHP_SELF'] !== '/' . basename(__FILE__) && file_exists(WWW_ROOT . $_SERVER['PHP_SELF'])) {
		return false;
	}
	$_SERVER['PHP_SELF'] = '/' . basename(__FILE__);
}

if (!is_file(VENDORS . 'autoload.php')) {
	mgr_boot_failure('Dependencies missing in ' . VENDORS . ' - run "composer install" in app/.');
}

require_once VENDORS . 'autoload.php';

if (!require_once 'Cake' . DS . 'bootstrap.php') {
	mgr_boot_failure('Framework core not found - run "composer install" in app/.');
}

App::uses('Dispatcher', 'Routing');

$Dispatcher = new Dispatcher();
$Dispatcher->dispatch(
	new CakeRequest(),
	new CakeResponse()
);
