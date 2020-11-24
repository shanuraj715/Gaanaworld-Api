<?php

/**
 * 
 */
class Functions
{
	private $connection;
	
	function __construct()
	{
		include '../db.php';
		$this -> connection = $conn;
	}

	function SingerIdToName( $singer_id ){
		$sql = "SELECT * FROM singers where singer_id = $singer_id";
		
	}
}
?>