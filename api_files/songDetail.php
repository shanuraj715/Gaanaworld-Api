<?php
include '../config.php';

/**
 * 
 */
class SongDetail
{
	public $where; // this is song_id

	private $sql_string;

	private $sql_obj;
	
	function __construct()
	{

		if(isset($_GET['where']) and !empty($_GET['where'])){
			$this -> where = $_GET['where'];
		}
	}

	function Data(){
		if($this -> where != ''){

		}
		include './classes/Sql_queries.php';
		$this -> createSqlQuery();
		$this -> sql_obj = new Query;
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
			$result['image'] = $this -> getImage( $result['image'], $result['upload_timestamp'] );
			$result['file_path'] = $this -> getFileName( $result['file_name'], $result['upload_timestamp']);
			$date = date('d-m-Y', $result['upload_timestamp']);
			$time = date('h:i A', $result['upload_timestamp']);
			$result['upload_date'] = $date;
			$result['upload_time'] = $time;
			$result['singer_name'] = $this -> singerIdToName ($result['singer']);
			$result['user_name'] = $this -> userIdToName ( $result['uploaded_by']);
			$result['album_name'] = $this -> albumIdToName( $result['album_id']);
			$result['category_name'] = $this -> catIdToName( $result['category_id'] );
			echo json_encode($result);
			$current_row++;
		}
		echo ']';
	}
	
	function getFileName( $file_name, $timestamp ){
	    $file_path = SITE_URL . 'uploads/' . date('m_Y', $timestamp) . '/' . $file_name;
	    return $file_path;
	}
	
	function getImage( $image_name, $timestamp ){
	    if($image_name == ''){
	        $image_name = rand(1,16) . '.png';
	        $image = SITE_URL . 'images/song_images/' . $image_name;
	    }
	    else{
	        $image = SITE_URL . 'uploads/' . date('m_Y', $timestamp) . '/images/' . $result['image'];
	    }
	    return $image;
	    
	}

	function createSqlQuery(){
		$string = "SELECT * FROM songs";

		if($this -> where != ''){

			$string .= " WHERE " . $this -> where;
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
	
	function userIdToName( $user_id ){
	    $string = "SELECT name from accounts WHERE user_id = $user_id";
		$this -> sql_obj -> sql = $string;
		$data = $this -> sql_obj -> Select();
		$user_name = mysqli_fetch_assoc($data);
		$user_name = $user_name['name'];
		return $user_name;
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

$new = new SongDetail;
$new -> Data();

?>