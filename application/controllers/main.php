<?php
namespace Controller;
use \View\View;

class Main extends Controller
{
	public function index()
	{
		$template = new View('main');
		$template->render();
	}
	
	public function second()
	{
		echo "Second view";
	}
}

?>
