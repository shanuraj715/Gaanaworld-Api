<?php
$site_url = '127.9.8.7/'; // this var is used after in this file to create a constant that would access across the whole site.

/* getting the protocol http:// or https:// */

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
	$site_protocol = "https://";
}
else{
	$site_protocol = "http://";
}

date_default_timezone_set('Asia/Kolkata');

$site_dir = __DIR__;
$site_dir = str_replace('\\', '/', $site_dir);
$site_dir .= '/';

define('SITE_DIR', $site_dir);
define("SITE_URL", $site_protocol . $site_url);

?>