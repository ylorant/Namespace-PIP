<?php
namespace Controller;

class Error extends Controller {
	
	function index()
	{
		$this->error404();
	}
	
	function error404()
	{
		echo '<h1>404 Error</h1>';
		echo '<p>Looks like this page doesn\'t exist</p>';
	}
    
    public function exception($e)
    {
		echo '<h1>Exception caught !</h1>';
		echo '<p>Exception caught in '.$e->getFile().' at line '.$e->getLine().' : '.$e->getMessage().'</p>';
		echo '<h2>Stack trace</h2>';
		echo '<pre>'.$e->getTraceAsString().'</pre>';
	}
}

?>
