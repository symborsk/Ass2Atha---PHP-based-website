<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'sys'); 
define('DB_USER','root'); 
define('DB_PASSWORD','symbor97');

function validateUrl($url){
	
	if (!filter_var($url, FILTER_VALIDATE_URL)) {
	   return false;
	}

	$headers = @get_headers($url);
	if( isset($headers[0]) And strpos($headers[0],'404') == false)
	{
	  return true;
	}
	else
	{
	  return false;
	}
}

function addBookmarkValue($val){
	$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);
	
	if (!$con) {
	    die('Cannot Connect to MYSQL Server');
	}

	$db_selected = mysqli_select_db($con, DB_NAME);
	if (!$db_selected) {
	    die ('Cant connect to db');
	}

	$user = $_SESSION['Username'];
	$query = mysqli_query($con, "Insert into bookmarks(Username,Bookmark) Value('" . $user . "','" . $val . "')");
	var_dump($query);
}

function updateLinkValue($oldValue, $newValue){
	$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);
	
	if (!$con ) {
	    die('Cannot Connect to MYSQL Server');
	}

	$db_selected = mysqli_select_db($con, DB_NAME);
	if (!$db_selected) {
	    die ('Cant connect to db');
	}

	$user = $_SESSION['Username'];
	$query = mysqli_query($con, "Update bookmarks Set Bookmark='" . $newValue . "' where Bookmark='" . $oldValue . "' and Username='" . $user . "'");

	var_dump($query);

	mysqli_close($con);
}

function deleteBookmarkValue($delete){
	$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);
	
	if (!$con ) {
	    die('Cannot Connect to MYSQL Server');
	}

	$db_selected = mysqli_select_db($con, DB_NAME);
	if (!$db_selected) {
	    die ('Cant connect to db');
	}

	$user = $_SESSION['Username'];
	$query = mysqli_query($con, "Delete from bookmarks where Bookmark='". $delete . "' and Username='" . $user . "'");

	mysqli_close($con);  
}

session_start();

if(isset($_GET['link'])){
	$link=$_GET['link'];
	if(validateUrl($link)){
		echo "valid link " . $link;
	}
	else{
		echo "invalid link " . $link;
	}
}

if(isset($_GET['priorEdit']) and isset($_GET['postEdit'])){	
	updateLinkValue($_GET['priorEdit'],$_GET['postEdit']);
}

if(isset($_GET['addValue'])){
	addBookmarkValue($_GET['addValue']);
}

if(isset($_GET['deleteValue'])){
	deleteBookmarkValue($_GET['deleteValue']);
}

if(isset($_GET['logout'])){
 	session_destroy();
    session_unset();
}

?>
