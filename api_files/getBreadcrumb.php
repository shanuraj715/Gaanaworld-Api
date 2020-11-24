<?php
include '../config.php';
include '../db.php';

$breadcrumb_array = [];

if(isset($_GET['song_id']) and is_numeric($_GET['song_id'])){
	$song_id = $_GET['song_id'];
}
else{
	$song_id = 0;
}

if(isset($_GET['category_id']) and is_numeric($_GET['category_id'])){
	$category_id = $_GET['category_id'];
}
else{
	$category_id = 0;
}

if($song_id != 0){

	$sql = "SELECT category_id from songs where song_id = $song_id";
	// $query = mysqli_query($conn, $sql);
	// if($query){
	//     $result = mysqli_fetch_assoc($query);
	//     $category_id = $result['category_id'];
	//     $category_name = catIdToName($result['category_id']);
	//     $arr = [$category_name => $result['category_id']];
	//     $breadcrumb_array = array_merge($breadcrumb_array, $arr);


	//     print_r($breadcrumb_array);
	//     $sql = "SELECT category_id from categories WHERE category_id = $parent_id";
	//     $query = mysqli_query($conn, $sql);
	//     if($query){
	//         $rows = mysqli_num_rows($query);
	//         if($rows == 1){
	//             $result = mysqli_fetch_assoc($query);
	//             $category_id = $result['category_id'];
	//             $category_name = catIdToName( $result['category_id'] );
	//             $arr = [$category_name => $category_id];
	//             $breadcrumb_array = array_merge( $breadcrumb_array, $arr );
	//             print_r($breadcrumb_array);
	//         }
	//     }
	// }


	$query = mysqli_query($conn, $sql);
	if($query){
		$result = mysqli_fetch_assoc($query);
		$category_name = catIdToName($result['category_id']);
		$category_id = $result['category_id'];
		$arr = [$category_name => $category_id];
		$breadcrumb_array = array_merge($breadcrumb_array, $arr);
		// print_r($breadcrumb_array);

		$sql = "SELECT * FROM categories WHERE category_id = $category_id";
		$query = mysqli_query($conn, $sql);
		if($query){
			$result = mysqli_fetch_assoc($query);
			$parent_id = $result['parent'];
			if($result['parent'] != 0){
				$parent_id = $result['parent'];
				while($parent_id != 0){

					$sql = "SELECT * from categories WHERE category_id = $parent_id";
					$query = mysqli_query($conn, $sql);
					if($query){
						$result = mysqli_fetch_assoc($query);
						$category_id = $result['category_id'];
						$category_name = catIdToName($result['category_id']);
						$arr = [$category_name => $category_id];
						$breadcrumb_array = array_merge($breadcrumb_array, $arr);
						$parent_id = $result['parent'];
					}
					else{
						$parent_id = 0;
					}
				}
			}
		}
		$breadcrumb_array = array_reverse($breadcrumb_array);
		$breadcrumb_array = array_merge($breadcrumb_array, $arr);
		
	}
}
elseif($category_id != 0){
	
	$sql = "SELECT * from categories WHERE category_id = $category_id";
	$query = mysqli_query($conn, $sql);
	if($query){
		$result = mysqli_fetch_assoc($query);
		$category_name = catIdToName($result['category_id']);
		$arr = [$category_name => $category_id];
		$breadcrumb_array = array_merge($breadcrumb_array, $arr);
		// print_r($breadcrumb_array);

		if($result['parent'] != 0){
			$parent_id = $result['parent'];
			while($parent_id != 0){
				$sql = "SELECT * from categories WHERE category_id = $parent_id";
				$query = mysqli_query($conn, $sql);
				if($query){
					$result = mysqli_fetch_assoc($query);
					$category_id = $result['category_id'];
					$category_name = catIdToName($result['category_id']);
					$arr = [$category_name => $category_id];
					$breadcrumb_array = array_merge($breadcrumb_array, $arr);
					$parent_id = $result['parent'];
				}
				else{
					$parent_id = 0;
				}
			}
		}
	}
	$breadcrumb_array = array_reverse($breadcrumb_array);
}

function catIdToName( $cat_id ){
	global $conn;
	$str = "SELECT category_name from categories WHERE category_id = $cat_id";
	$query = mysqli_query($conn, $str);
	if($query){
		$result = mysqli_fetch_assoc($query);
		return $result['category_name'];
	}
	else{
		return "Unknown category";
	}

}
echo '[';
echo json_encode($breadcrumb_array);
echo ']';
?>