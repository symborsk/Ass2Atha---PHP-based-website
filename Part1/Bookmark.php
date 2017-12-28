<!DOCTYPE html>

<html lang="en">
<head>

<title>Webpage John Symborski</title>
<link href="../Shared/tma2_style.css" rel="stylesheet" type="text/css">
<script src="Bookmark.js"></script>
</head>

<body>

<?php 
session_start();
?>

<div>	
	<nav>
		<ul class="navList">
		 <?php 
			if(isset($_SESSION['Username'])){
	    		print("<li><a onclick=\"logoutUser()\" href=\"#\">Sign Out</a></li>");
		    }
		    else{
		    	print("<li><a onclick=\"popLoginForm()\" href=\"#\">Sign In</a></li>");
		    }
		    ?>
		  
		  <li><a href="..\tma2.htm">Home</a></li>
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
<span class="close" onclick="closeLogin()">&times;</span>
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
	<span class="close" onclick="closeEditNew()">&times;</span>
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
		<h6 style="margin-bottom: 0px"><?php
			if(isset($_SESSION['Username'])){
	    		print("Profile:" . $_SESSION['Username']);
	    	}
		?></h6>
	    <h1 style="margin-top: 0px" class="title">  
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

		$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);

		if (!$con) {
    		die('Cannot Connect to MYSQL Server');
		}

		$db_selected = mysqli_select_db($con, DB_NAME);
		if (!$db_selected) {
   	 		die ('Cant connect to db');
		}

		if(isset($_SESSION['Username'])){
			$user  = $_SESSION['Username'];

			print("<h2>Your Stored Bookmarks: </h2><ul class=\"linkList\">");
			
			$queryResult = mysqli_query($con, "Select Bookmark from bookmarks where Username='" . $user ."'");
			while ($row = mysqli_fetch_assoc($queryResult)){
				
				print("<li><span class=\"link\"><a target=\"_blank\" href=\"". $row["Bookmark"] . "\">" . $row["Bookmark"] . "</a></span>");
				print("<input type=\"button\" class=\"editButton\" onClick=\"editLink('" . $row["Bookmark"] . "')\" value=\"Edit\"/>");
				print("<input class=\"deleteButton\" type=\"button\" onClick=\"deleteLink('" . $row["Bookmark"] . "')\" value=\"Delete\"/></li>");
			}
			print("</ul>");	

			print("<div style=\"text-align:center;\"><input class=\"addButton\" type=\"button\" onClick=\"addLink()\"  value=\"Add Bookmark\"\></div> ");

		}
		Else
		{
			print("<h2>Our Most Used Bookmarks</h2><ul class=\"linkList\">");
			
			$queryResult = mysqli_query($con, "Select Bookmark, Count(Bookmark) as bookmarkCount from bookmarks GROUP BY Bookmark ORDER BY bookmarkCount DESC limit 10");

			
			while ($row = mysqli_fetch_assoc($queryResult)){
				print("<li><a target=\"_blank\" href=\"". $row["Bookmark"] . "\">" . $row["Bookmark"] . "</a></li>");
			}

			print("</ul>");	
		}

		mysqli_close($con);	
	?>
	   
</div>

</body>