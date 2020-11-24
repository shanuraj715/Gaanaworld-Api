<?php
include '../config.php';
/**
 * 
 */
class RelatedFiles
{

	public $category;
	public $album;
	public $limit;

	private $sql_string;
	private $sql_obj;
	
	function __construct()
	{
		include './classes/Sql_queries.php';
		$this -> sql_obj = new Query;

		if(isset($_GET['category_id']) and is_numeric($_GET['category_id'])){
			$this -> category = $_GET['category_id'];
		}

		if(isset($_GET['album_id']) and is_numeric($_GET['album_id'])){
			$this -> album = $_GET['album_id'];
		}

		if(isset($_GET['limit']) and !empty($_GET['limit'])){
			$this -> limit = $_GET['limit'];
		}
	}

	function List(){
		$this -> createSqlQuery();
		$this -> sql_obj -> sql = $this -> sql_string;
		$data = $this -> sql_obj -> Select();

		$array = [];

		$rows = mysqli_num_rows($data);
		echo '[';

		if($rows != 0){
			$current_row = 0;
			while( $result = mysqli_fetch_assoc($data)){
				// $array = array_merge($array, $result);
				if($current_row != 0){
					echo ',';
				}
				$result['category_name'] = $this -> catIdToName( $result['category_id'] );
				$result['album_name'] = $this -> albumIdToName( $result['album_id'] );
				$result['singer_name'] = $this -> singerIdToName( $result['singer'] );
				echo json_encode($result);
				$current_row++;
			}

			if( $rows < $this -> limit){
				$limit = $this -> limit - $rows;
				$category_id = $this -> category;
				$this -> sql_string = "SELECT * FROM songs WHERE category_id = $category_id ORDER BY RAND() LIMIT $limit";
				$this -> sql_obj-> sql = $this -> sql_string;
				$data = $this -> sql_obj -> select();

				$rows2 = mysqli_num_rows($data);
				if($rows2 != 0){
					if($rows != 0){
						echo ',';
					}
					$current_row = 0;
					while( $result2 = mysqli_fetch_assoc($data)){
						if($current_row != 0){
							echo ',';
						}
						$result['category_name'] = $this -> catIdToName( $result2['category_id'] );
						$result['album_name'] = $this -> albumIdToName( $result2['album_id'] );
						$result['singer_name'] = $this -> singerIdToName( $result2['singer'] );
						echo json_encode($result2);
						$current_row++;
					}
				}
			}
		}
		// $this -> JSON( $ );
		echo ']';
	}

	function JSON( $data ){
		$current_row = 0;
		while($result = mysqli_fetch_assoc($data)){
			if($current_row != 0){
				echo ',';
			}
			echo htmlspecialchars(json_encode($result));
			$current_row++;
		}
	}

	function createSqlQuery(){
		$album_id = $this -> album;
		$category_id = $this -> category;
		$limit = $this -> limit;
		$string = "SELECT * FROM songs WHERE album_id = $album_id and category_id = $category_id ORDER BY RAND() LIMIT $limit";

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

$related_files = new RelatedFiles;
$related_files -> List();
?>