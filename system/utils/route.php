<?php

/* Predefined route ("static routes") class container
 * Route types:
 * 	- Static: pure static route, specifying a direct relation path->function.
 * 
 * 	- Dynamic (simple/reflexive): Dynamic route, but with only simple parameters (easy replacement for regexes, allow to do reflexive
 * 	  replacements):
 * 		-> integers: {int}
 * 		-> strings: {string}
 * 		-> a function which returns the string which will be in the path: {f:Class::method}. The URL will be transmitted as the unique parameter.
 * 		-> a class: {class}.
 * 		-> a method: {method}. If a class has already been guessed, then the method will be check in it.
 * 		-> a method from a defined class: {m:Class}.
 *	  Parameters will be sent to the function as arguments, in order of appearance.
 * 	  For class names: If they're not fully namespaced (starting with an antislash), they will me namespaced into the Controller namespace.
 *    Keep in mind that this analysis is really resource-hungry.
 *    The path has to be an array.
 * 
 * 	- Dynamic (RegExp): Dynamic route, using a full PCRE regex (delimiters and options included).
 * 
 * Note: No need to use the prefix Controller\\ before the class name (to specify the namespace).
 * It is assumed that the mapped class is a controller.
 * 
 * Examples of usage: 
 * 	- $route = new Route(Route::TYPE_STATIC, 'post/new', 'Post::create');
 * 	- $route = new Route(Route::TYPE_DYNAMIC, 'view/{int}', 'Post::view');
 * 	- $route = new Route(Route::TYPE_REGEXP, '#^edit/([0-9]+)/(comment|data)$#', 'Post::edit');
 */

class Route
{
	public $type = Route::TYPE_STATIC; //The route type
	public $path; //The path to check
	public $mapping; //The mapped class/method
	public $matches = array(); //The eventual var matches of an url
	public $separator = '/'; //The separator for dynamic matching
	public $callback = null; //The optionnal callback that will be called when the route is matched
	
	const TYPE_STATIC = 0x01;
	const TYPE_DYNAMIC = 0x02;
	const TYPE_REGEXP = 0x03;
	
	public function __construct($type = Route::TYPE_STATIC, $path = '', $mapping = null, $separator = '/', $callback = null)
	{
		$this->type = $type;
		$this->path = $path;
		$this->mapping = $mapping;
	}
}


class Router
{
	private $defaultRoute = null;
	private $routes = array();
	
	public function route($url)
	{
		if(!empty($this->routes))
		{
			foreach($this->routes as $route)
			{
				if($this->match($url, $route))
				{
					$call = explode('::', $this->map($route));
					
					if(strpos($call[0], 'Controller\\') === FALSE)
						$call[0] = '\\Controller\\'.$call[0];
					
					$class = $call[0];
					$func = $call[1];
					$obj = new $class();
					
					if($route->callback !== null)
					{
						$cb = $route->callback;
						$cb($obj);
					}
					
					return array("func" => array($obj, $func), "param" => $route->matches);
				}
			}
		}
		
		return null;
	}
	
	public function setDefaultRoute(Route $route)
	{
		$this->defaultRoute = $route;
	}
	
	public function addRoute(Route $route)
	{
		$this->routes[] = $route;
	}
	
	public function addRoutes(array $routes)
	{
		foreach($routes as $route)
			$this->addRoute($route);
	}
	
	public function map(Route $route)
	{
		$mapping = $route->mapping;
		
		foreach($route->matches as $id => $value)
		{
			if(strpos($mapping, '$'.($id+1)) !== FALSE)
			{
				
				$mapping = str_replace('$'.($id+1), $value, $mapping);
				unset($route->matches[$id]);
			}
		}
		
		return $mapping;
	}
	
	public function __get($name)
	{
		if(isset($this->$name))
			return $this->$name;
		else
			throw new UnknownPropertyException($name);
	}
	
	private function match($url, Route $route)
	{
		switch($route->type)
		{
			case Route::TYPE_STATIC: //Static routes
				return $url == $route->path;
			case Route::TYPE_REGEXP: //Regex
				return preg_match($route->path, $url, $route->matches);
				break;
			case Route::TYPE_DYNAMIC: //Dynamic routes
				$route->matches = array();
				$path = $route->path;
				
				$pieces = explode($route->separator, $url);
				$j = 0;
				$k = -1;
				$loadedClass = null;
				foreach($pieces as $i => $el)
				{
					$capture = false;
					if(!isset($path[$k]) || $path[$k][0] != '[' || $path[$k][strlen($path[$k])-1] != ']')
					{
						$k++;
						if(!isset($path[$k]))
							return false;
						if($path[$k][0] != '[' || $path[$k][strlen($path[$k])-1] != ']')
							$currentpath = $path[$k];
						else
							$currentpath = substr($path[$k], 1, -1);
					}
					else
						$currentpath = substr($path[$k], 1, -1);
					
					if(substr($currentpath, 0, 3) == '{f:')
					{
						$class = substr($path[$k], 3, -1);
						$call = explode('::', $class);
						if($call[0][0] != '\\')
									$call[0] = 'Controller\\'.$call[0];
						$func = $call[1];
						$obj = new $call[0]();
						$currentpath = $obj->$func($url);
						$capture = true;
					}
					
					if(substr($currentpath, 0, 3) == '{m:')
					{
						$class = substr($currentpath, 3, -1);
						if($class[0] != '\\')
							$class = 'Controller\\'.$class;
						
						//Try to load the class. Failing to load it will result in a false return.
						try
						{
							if(!class_exists($class))
								__autoload($class);
						}
						catch(\Exception\UnknownClassException $e)
						{
							return false;
						}
						
						if(!method_exists($class, $el))
							return false;
						$loadedClass = $class;
						$capture = true;
					}
					elseif($currentpath == '{class}')
					{
						$class = $el;
						if($class[0] != '\\')
							$class = 'Controller\\'.$class;
						//Try to load the class. Failing to load it will result in a false return.
						try
						{
							if(!class_exists($class))
								__autoload($class);
						}
						catch(\Exception\UnknownClassException $e)
						{
							return false;
						}
						$loadedClass = $class;
						$route->matches[$j++] = $class;
					}
					elseif($currentpath == '{method}')
					{
						if($loadedClass === null)
							return false;
						
						if(!method_exists($loadedClass, $el))
							return false;
							
						$capture = true;
					}
					elseif($currentpath == '{int}')
					{
						if(!is_numeric($el))
							return false;
						$capture = true;
						$el = intval($el);
					}
					elseif($currentpath == '{string}')
					{
						$capture = true;
					}
					else
					{
						if($el != $currentpath)
							return false;
					}
					
					if($capture == true)
						$route->matches[$j++] = $el;
				}
				return true;
				break;
			default:
				return false;
		}
	}
}
