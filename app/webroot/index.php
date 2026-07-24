<?php
/**
 * The Front Controller for handling every request
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.webroot
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

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

if (!is_dir(VENDORS)) {
	trigger_error(
		'Composer vendors directory not found at "' . VENDORS . '". ' .
		'Please run "composer install" in the app/ directory to install dependencies.',
		E_USER_ERROR
	);
}

if (!is_file(VENDORS . 'autoload.php')) {
	trigger_error(
		'Composer autoload file not found at "' . VENDORS . 'autoload.php". ' .
		'Please run "composer install" in the app/ directory to generate the autoload file.',
		E_USER_ERROR
	);
}

require_once VENDORS . 'autoload.php';

if (!require_once 'Cake' . DS . 'bootstrap.php') {
	trigger_error(
		'CakePHP core could not be found. ' .
		'Please run "composer require pieceofcake2/cakephp" in the app/ directory.',
		E_USER_ERROR
	);
}

App::uses('Dispatcher', 'Routing');

$Dispatcher = new Dispatcher();
$Dispatcher->dispatch(
	new CakeRequest(),
	new CakeResponse()
);
