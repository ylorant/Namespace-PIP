<?php 

$config['base_url'] = '/'; // Base URL including trailing slash (e.g. http://localhost/)

$config['default_controller'] = 'Controller\\Main'; // Default controller to load
$config['default_controller_file'] = 'main.php'; // Default controller file to load
$config['error_controller'] = 'Controller\\Error'; // Controller used for errors (e.g. 404, 500 etc)
$config['error_controller_file'] = 'error.php'; // Controller file used for errors (e.g. 404, 500 etc)

define('DB_ENGINE', 'mysql'); //Database engine (actually, only mysql works)
define('DB_HOST', 'localhost'); //Database host
define('DB_PORT', 3306); //Database port, by default 3306
define('DB_USER', ''); //Database user
define('DB_PW', ''); //Database user password
define('DB_DBNAME', ''); //Database name


?>
