<?php

/**
 * 
 */
class Query
{
	public $sql;
	private $connection;
	
	function __construct()
	{
		include '../db.php';
		$this -> connection = $conn;
	}

	function Select(){
		// $this -> sql = mysqli_real_escape_string($this -> connection, $this -> sql);
		$query = mysqli_query($this -> connection, $this -> sql);
		if($query){
			return $query;
		}
		else{
			echo mysqli_error($this -> connection);
			return false;
		}
	}

	function MRES( $data ){
		return mysqli_real_escape_string($this -> connection, $data);
	}


}
?>