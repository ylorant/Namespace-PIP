<?php
namespace Controller;
use \Exception;
use \View\View;

class Main extends Controller {
	
	function index()
	{
		$template = new View('main');
		$template->render();
	}
}

?>
