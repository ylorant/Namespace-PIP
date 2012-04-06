<?php
namespace Model;
use \PDO;

class Model {

	protected $_PDO;
	protected $_query;
	protected $_curParamID = 1;
	
	public function __construct()
	{
                ob_start();
		$this->_PDO = DatabaseHandler::get();
		call_user_func_array(array($this, '__init'), func_get_args());
	}
	
	public function __init()
	{
		
	}
	
	public static function toArray($object)
	{
		$array = array();
		foreach($object as $name => $value)
		{
			if(!is_object($value))
				$array[$name] = $value;
			else
				$array[$name] = self::toArray($value);
		}
		
		return $array;
	}
	
	public static function serialize($object)
	{
		$data = array();
		foreach($object as $name => $value)
		{
			if($name[0] != '_')
			{
				if(is_object($value))
					$data[$name] = 'object/'.get_class($value).':'.Model::serialize($value);
				else
					$data[$name] = $value;
			}
		}
		
		return serialize($data);
	}
	
	public static function unserialize($data, $object)
	{
		foreach(unserialize($data) as $name => $value)
		{
			if(strpos($value,'/'))
			{
				$parts = explode(':', $value, 2);
				$parts[0] = explode('/', $parts[0], 2);
				if($parts[0][0] == 'object')
				{
					$objName = $parts[0][1];
					$value = new $objName();
					Model::unserialize($parts[1], $value);
				}
			}
			
			$object->$name = $value;
		}
	}
	
	public function prepare($query)
	{
		$this->_query = $this->_PDO->prepare($query);
		
		return $this->_query;
	}
	
	public function bind($name, $value = NULL)
	{
		if($value === NULL)
			list($value, $name) = array($name, ++$this->_curParamID);
		
		$type = PDO::PARAM_STR;
		if(is_int($value))
			$type = PDO::PARAM_INT;
		
		$this->_query->bindValue($name, $value, $type);
	}
	
	public function execute($query = NULL)
	{
		$values = array();
		
		$arglist = func_get_args();
		if(is_object($query) && $query instanceof PDOStatement)
			array_shift($arglist);
		else
			$query = $this->_query;
		
		foreach($arglist as $arg)
			$values[] = $arg;
		
		return $query->execute($values);
	}
	
	public function fetch()
	{
		return $this->_query->fetch(PDO::FETCH_ASSOC);
	}
	
	public function fetchAll()
	{
		return $this->_query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function reset()
	{
		$this->_query->resetCursor();
	}
	
	public function drop()
	{
		unset($this->_query);
	}
	
	public function lastInsertID()
	{
		return $this->_PDO->lastInsertId();
	}
    
    public function loadModel($name)
	{
		require_once(APP_DIR .'models/'. strtolower($name) .'.php');
		
		$name = '\\Model\\'.$name;
		$model = new $name;
		return $model;
	}
}

class DatabaseHandler
{
	private static $_pdo;
	public static function get()
	{
		if(self::$_pdo === NULL)
		{
			self::$_pdo = new PDO(DB_ENGINE.':host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_DBNAME, DB_USER, DB_PW);
			self::$_pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			self::$_pdo->exec('SET NAMES utf8');
		}
		
		return self::$_pdo;
	}
}

?>
