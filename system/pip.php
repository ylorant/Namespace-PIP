<?php
namespace Controller;
use \Exception, \Debug, \Router;
use \View\View;

function pip()
{
	global $config;
    
    // Set our defaults
    $controller = $config['default_controller'];
    $action = 'index';
    $url = '';
    
	// Get request url and script url
	$request_url = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
	$script_url  = (isset($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : '';
    
	// Get our url path and trim the / of the left and the right
	if($request_url != $script_url) $url = trim(preg_replace('/'. str_replace('/', '\/', str_replace('index.php', '', $script_url)) .'/', '', $request_url, 1), '/');
	
	define('REQUESTED_PAGE', $url);
	
	//         //
	// Routing //
	//         //
	
	$router = new Router();
	$router->addRoutes($config['routes']);
	$callback = $router->route($url);
    
    if(empty($callback))
    {
		// Error handling (404)
		$controller = $config['error_controller'];
		$action = 'index';
	
		// Create object and parameters
		$obj = new $controller;
		$callback = array("func" => array($obj, $action), "param" => null);
	}
	
	
    call_user_func_array($callback["func"], $callback["param"]);
    
    die();
}

?>
