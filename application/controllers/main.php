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
}

?>
