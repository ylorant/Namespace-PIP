<?php 

$config['base_url'] = '/Namespace-PIP/'; // Base URL including trailing slash (e.g. http://localhost/)

$config['default_controller'] = 'Controller\\Main'; // Default controller to load
$config['default_controller_file'] = 'main.php'; // Default controller to load
$config['error_controller'] = 'Controller\\Error'; // Controller used for errors (e.g. 404, 500 etc)
$config['error_controller_file'] = 'error.php'; // Controller used for errors (e.g. 404, 500 etc)
$config['exception_handler'] = 'exception';
$config['session_error_handler'] = 'session_error';

define('DB_ENGINE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_USER', 'root');
define('DB_PW', 'root');
define('DB_DBNAME', 'pip');

?>
