<!DOCTYPE html>

<?
php session_start();
?>

<html lang="en">
<head>

<title>Webpage John Symborski</title>
<link href="../Shared/tma2_style.css" rel="stylesheet" type="text/css">
<script src="Bookmark.js"></script>
</head>

<body>

<div>	
	<nav>
		<ul class="navList">
		  <li><a onclick="popLoginForm()" href="#">Sign In</a></li>
		  <li><a href="news.asp">News</a></li>
		  <li><a href="contact.asp">Contact</a></li>
		  <li><a href="about.asp">About</a></li>
		</ul>
	</nav>

<div id="modalDialogLogin" class="modalLogin"
<?php 
	if(!isset($_SESSION['ShowForm']))
	{
		print(" style=\"display:none\"");
	}
	elseif($_SESSION['ShowForm'] == "True")
	{
		print(" style=\"display:block\"");
		unset($_SESSION["ShowForm"]);
	}
	else
	{
		print(" style=\"display:none\"");
	}
?>
>
  <form method='post' class="modalForm" action='login.php' >
    <div class="Title">
        <h1 class="title">  
	    Bookmarks Are Us
	    </h1>	   
    </div>
     <div class="errorText">
     	<?php 
    	if(isset($_SESSION['Error'])){
    		print($_SESSION['Error']);
    		unset($_SESSION['Error']);
    	}?></div>
    <div class="container">
	  	<label><b>Username</b></label>
		<input type="text" placeholder="Enter Username" name="uname" required>

	  	<label><b>Password</b></label>
	  	<input type="password" placeholder="Enter Password" name="psw" required>

	  	<div style="text-align:center;">
		  	<button type="submit" name="Login">Login</button>
		  	<button type="submit" name="Create">Create User</button>
		</div>
    </div>
  </form>
</div>


<div id="modalDialogEdit" class="modalEdit">
  <form method='post' class="modalForm" onsubmit=" return ConfirmNewLink()">
    <div class="Title">
        <h1 class="title">  
	    Bookmarks Are Us
	    </h1>	   
    </div>
    <div id="editErrorText" class="errorText">

    </div>
    <div class="container">
	  	<label><b id="currentLinkEdit"></b></label>
		<input id="newLinkEdit" type="url" name="newLink" required>
	</div>
  	<div style="text-align:center;">
		<button type="submit" name="Edit">Confirm</button>
	</div>
</div>

<div class="content" >
	<header>
		<h3>Profile: 
		<?php
			if(isset($_SESSION['Username'])){
	    		print($_SESSION['Username']);
	    	}
		?></h5>
	    <h1 class="title">  
	    Bookmarks Are Us
	    </h1>
	    <h2>
	    Assignment 2 Part 1 - Bookmarking Server
	    </h2> 
	    <h3>
	    John Symborski, 33339305 
	    </h3>
	</header>

	<hr>

	<h1 style="text-align:center;">Welcome to Bookmarks Are Us! Your Trusted Source For Bookmarking.</h1>
	<?php
		define('DB_HOST', 'localhost');
		define('DB_NAME', 'sys'); 
		define('DB_USER','root'); 
		define('DB_PASSWORD','symbor97');

		if(isset($_SESSION['Username'])){
			print("<h2>Your Bookmarks</h2>");

		}
		Else
		{
			$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);

			if (!$con) {
	    		die('Cannot Connect to MYSQL Server');
			}

			$db_selected = mysqli_select_db($con, DB_NAME);
			if (!$db_selected) {
	   	 		die ('Cant connect to db');
			}

			print("<h2>Our Most Used Bookmarks</h2><ul class=\"linkList\">");
			
			$queryResult = mysqli_query($con, "Select Bookmark, Count(Bookmark) as bookmarkCount from bookmarks GROUP BY Bookmark ORDER BY bookmarkCount DESC limit 10");

			while ($row = mysqli_fetch_assoc($queryResult)){
				print("<li><a target=\"_blank\" href=\"".$row["Bookmark"] . "\">" . $row["Bookmark"] . "</a></li>");
			}

			print("</ul>");	
		}	
	?>

	<!-- <ul>
		<li>
			<span class="link" style="width:30%">www.lalala.com</span>
			<span class="editButton" style="margin-left:30%">
				<input type="button" onClick="editLink('www.lalala.com')" value="Edit"/>
			</span>

			<span class="deleteButton">
				<input type="button" onClick="deleteLink('www.lalala.com')" value="Delete"/>
			</span>
		</li>
	</ul> -->
	   
</div>

</body>