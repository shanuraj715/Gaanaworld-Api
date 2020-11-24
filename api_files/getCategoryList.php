<?php
include '../config.php';

/**
 * 
 */
class SongList
{
	public $where;
	public $order_by;
	public $limit;

	private $sql_string;
	private $this -> sql_obj;
	
	function __construct()
	{
		include './classes/Sql_queries.php';
		$this -> sql_obj = new Query;

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
		$this -> createSqlQuery();
		$this -> sql_obj -> sql = $this -> sql_string;
		$data = $this -> sql_obj -> Select();
		$this -> JSON( $data );
	}

	function JSON( $data ){
		$current_row = 0;
		echo '[';
		while($result = mysqli_fetch_assoc($data)){
			if($current_row != 0){
				echo ',';
			}
			echo json_encode($result);
			$current_row++;
		}
		echo ']';
	}

	function createSqlQuery(){
		$string = "SELECT * FROM categories";

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

	function singerIdToName( $singer_id ){
		$string = "SELECT singer_name from singers WHERE singer_id = $singer_id";
		$this -> sql_obj -> sql = $string;
		$data = $this -> sql_obj -> Select();
		$singer_name = mysqli_fetch_assoc($data);
		$singer_name = $singer_name['singer_name'];
		return $singer_name;
	}
	
	function albumIdToName ($album_id){
	    $string = "SELECT album_name from albums WHERE album_id = $album_id";
		$this -> sql_obj -> sql = $string;
		$data = $this -> sql_obj -> Select();
		$album_name = mysqli_fetch_assoc($data);
		$album_name = $album_name['album_name'];
		return $album_name;
	}
	
	function catIdToName( $category_id ){
	    $string = "SELECT category_name from categories WHERE category_id = $category_id";
		$this -> sql_obj -> sql = $string;
		$data = $this -> sql_obj -> Select();
		$category_name = mysqli_fetch_assoc($data);
		$category_name = $category_name['category_name'];
		return $category_name;
	}
}

$new = new SongList;
$new -> List();

?>