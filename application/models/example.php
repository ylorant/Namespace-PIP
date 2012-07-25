<?php
namespace Model;

class Example extends Model
{
	public function getSomething($id)
	{
		$this->prepare('SELECT * FROM something WHERE id = ?');
		$this->execute($id);
		
		return $this->fetch();
	}

}

?>
