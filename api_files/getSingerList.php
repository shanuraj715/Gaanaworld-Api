<?php


/**
 * 
 */
class SingerList
{
	public $where;
	public $order_by;
	public $limit;

	private $sql_string;
	
	function __construct()
	{
		include '../config.php';

		if(isset($_GET['where']) and !empty($_GET['where'])){
			$this -> where = $_GET['where'];
		}

		if(isset($_GET['order_by']) and !empty($_GET['order_by'])){
			$this -> order_by = $_GET['order_by'];
		}

		if(isset($_GET['limit']) and !empty($_GET['limit'])){
			$this -> limit = $_GET['limit'];
		}
	}

	function List(){
		include './classes/Sql_queries.php';
		$this -> createSqlQuery();
		$sql_obj = new Query;
		$sql_obj -> sql = $this -> sql_string;
		$data = $sql_obj -> Select();
		$this -> JSON( $data );
	}

	function JSON( $data ){
		$current_row = 0;
		echo '[';
		while($result = mysqli_fetch_assoc($data)){
			if($current_row != 0){
				echo ',';
			}
			echo htmlspecialchars(json_encode($result));
			$current_row++;
		}
		echo ']';
	}

	function createSqlQuery(){
		$string = "SELECT * FROM singers";

		if($this -> where != ''){

			$string .= " WHERE " . $this -> where;
		}

		if($this -> order_by != ''){
			$string .= ' ORDER BY ' . $this -> order_by;
		}

		if($this -> limit != ''){
			$string .= ' LIMIT ' . $this -> limit;
		}

		$this -> sql_string = $string;
	}
}

$new = new SingerList;
$new -> List();

?>