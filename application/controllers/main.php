<?php
namespace Controller;

class Main extends Controller {
	
	function index()
	{
		$template = $this->loadView('main');
		$template->render();
	}
    
}

?>
