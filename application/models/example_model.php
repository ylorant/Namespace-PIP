<?php
namespace Model;

class Example extends Model {
	
	public function getSomething($id)
	{
		$id = $this->escapeString($id);
		$result = $this->query('SELECT * FROM something WHERE id="'. $id .'"');
		return $result;
	}

}

?>
