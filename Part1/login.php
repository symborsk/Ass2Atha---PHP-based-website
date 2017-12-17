<!DOCTYPE html>

<?php session_start();?>

<html>

<head>
	<title>Login.php</title>
</head>
<body>

<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'sys'); 
define('DB_USER','root'); 
define('DB_PASSWORD','symbor97');

$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);
if (!$con ) {
    die('Cannot Connect to MYSQL Server');
}

$db_selected = mysqli_select_db($con, DB_NAME);
if (!$db_selected) {
    die ('Cant connect to db');
}

$username = $_POST["uname"];
$pass = $_POST["psw"];

if(isset($_POST['Login']))
{
	AttemptLogin($username, $pass, $con);
}
else
{
	AttemptCreateUser($username, $pass, $con);
}

mysqli_close($con);
header("Location: Bookmark.php");
exit;

function AttemptLogin($us, $pas, $connection)
{
	$result = mysqli_query($connection, "Select * from login where Username ='" . $us . "' and Password='" . $pas . "'");
	if(mysqli_num_rows($result) == 1)
	{
		$_SESSION['Username'] = $us;
		$_SESSION['ShowForm'] = "False";
	}
	else
	{
		$_SESSION['Error'] = "Incorrect Username or Password";
		$_SESSION['ShowForm'] = "True";
	}
}

function AttemptCreateUser($us, $pas, $connection)
{
	$result = mysqli_query($connection, "Select * from login where Username ='" . $us . "'");
	if(mysqli_num_rows($result) == 0)
	{
		$query = mysqli_query($connection, "Insert into login(Username, Password) Values('" . $us . "', '" . $pas . "')");

		$_SESSION['Username'] = $us;
		$_SESSION['ShowForm'] = "False";
	}
	else
	{
		$_SESSION['Error'] = "Username already exist please pick a different one";
		$_SESSION['ShowForm'] = "True";
	}
}

?>
</body>	
</html>
