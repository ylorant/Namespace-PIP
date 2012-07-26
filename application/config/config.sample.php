<?php 

$config['base_url'] = '/'; // Base URL including trailing slash (e.g. http://localhost/)

$config['default_controller'] = 'Controller\\Main'; // Default controller to load
$config['default_controller_file'] = 'main.php'; // Default controller file to load
$config['error_controller'] = 'Controller\\Error'; // Controller used for errors (e.g. 404, 500 etc)
$config['error_controller_file'] = 'error.php'; // Controller file used for errors (e.g. 404, 500 etc)
$config['exception_handler'] = 'exception'; //Exception handler method (from error_controller)
$config['debug'] = true; //Debug activation
$config['hash_salt'] = ''; //Hash salt for passwords

// Routes
// These reproduce the way of routing addresses coming from older versions of PIP
$config['routes'] = array(
	new Route(Route::TYPE_STATIC, '', 'Main::index'),
	new Route(Route::TYPE_DYNAMIC, array('{class}', '{method}', '[{string}]'), '$1::$2'),
	new Route(Route::TYPE_DYNAMIC, array('{class}', '[{string}]'), '$1::index'),
	new Route(Route::TYPE_DYNAMIC, array('{m:Main}', '[{string}]'), 'Main::$1')
);

define('DB_ENGINE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_USER', '');
define('DB_PW', '');
define('DB_DBNAME', '');
?>
