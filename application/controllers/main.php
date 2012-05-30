<?php
namespace Controller;
use \Exception;

class Main extends Controller {
	
	function index()
	{
		$template = $this->loadView('main');
		$template->render();
	}
    
    function exception()
    {
		throw new Exception("error");
	}
}

?>
