<?php
// rename this file to db.php
$db['host']         = 'localhost';
$db['username']     = '';
$db['password']     = '';
$db['db_name']      = '';

foreach($db as $key => $value){
    define(strtoupper($key), $value);
}

$conn = mysqli_connect(HOST, USERNAME, PASSWORD, DB_NAME);


if(!$conn){
    die("Unable to connect to the database.");
}

?>