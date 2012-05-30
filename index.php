<?php
/*
 * PIP v0.5.3
 */

//Start the Session
session_start(); 

// Defines
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
define('APP_DIR', ROOT_DIR .'application/');
if(!$_SERVER['QUERY_STRING'])
	define('CURRENT_PAGE', '/index');
else
	define('CURRENT_PAGE', $_SERVER['QUERY_STRING']);

// Includes
require(APP_DIR .'config/config.php');
require(ROOT_DIR .'system/model.php');
require(ROOT_DIR .'system/view.php');
require(ROOT_DIR .'system/controller.php');
require(ROOT_DIR .'system/pip.php');
require(ROOT_DIR .'system/miscfunc.php');

// Define base URL
global $config;
set_exception_handler('exception_handler');
define('BASE_URL', $config['base_url']);
Controller\pip();

?>
