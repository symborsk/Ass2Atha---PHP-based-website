<!DOCTYPE html>

<?php session_start();?>

<html>

<head>
	<title>Academic Service</title>
	<link href="../Shared/tma2_style.css" rel="stylesheet" type="text/css">
	<script src="AcademicService.js"></script>
</head>
<body>
	<div>	
	<nav>
		<ul class="navList">
		  <li><a href="..\tma2.htm">Home</a></li>
		  <li><a href="#" onclick="courseLoadShow()">Course Load</a></li>
		  <li><a href ="#" onclick="lastLoadedCourseShow()">Last Loased Course</a></li>
		  <li><a href="#" onclick="uploadContentShow()">Upload Content</a></li>
		</ul>
	</nav>
</div>
<div class="content" >

	<header>
	    <h1>  
	    Assignment #2 COMP 466
	    </h1>
	    <h2>
	    Advanced Technologies for Web-Based Systems
	    </h2> 
	    <h3>
	    John Symborski, 33339305 
	    </h3>
	    <h4>
	    Dec 12th 2017 - TBD  Hours Spent: TBD
	    </h4>
	</header>

	<hr style="margin:0px"> 
</div>

<div class="mainWindow">
	<h1 id="MainTitle">Search For Assignments/Quizzes/Lessons</h1>
	<h2 id="SubTitle">You will need to know the password for the course in order to access it!</h2>
</div>

<div id="courseSearch" class="searchBar">
	<form method='post' class="embeddedForm" action='courseContent.php' >
		<label><b>Course Selection</b></label>
		<select class="courseList" id="courseList">
			<option value="Math101">Math 101</option>
			<option value="English101">English 101</option>
		</select>
		<?php
		?>
		<label><b>Course Password</b></label>
		<input type="password" placeholder="Enter a student course password" name="coursePassword" required> 
	 	<div style="text-align:center;">
			<button type="submit" name="showContent">Show Content</button>
		</div>
	</form>
</div>

<div id="uploadCourse" class="uploadCourse">
	<form method='post' class="embeddedForm" action='uploadCourse.php' enctype="multipart/form-data" >
			<label><b>Course Name: </b></label>
			<input type="text" placeholder="Select existing or enter new course name" name="courseName" list="courses"  required>
				<datalist id="courses">
	  				<option>Math 101</option> 
	  				<option>English 101</option>
				</datalist>

			<label><b>Unit: </b></label>
			<input type="text" placeholder="Enter the course unit" name="courseUnit" required>  
			
			<label><b>Type: </b></label>
			<select class="courseList" id="uploadType" name="docType">
				<option value="Assignment">Assignment</option>
				<option value="Lesson">Lesson</option>
				<option value="Quiz">Quiz</option>
			</select>			
			<?php
			?>
			
			<label><b>Course Password: </b></label>
			<input type="password" placeholder="Enter a course password" name="coursePassword" required>
			
			<label><b>Admin Password: </b></label>
			<input type="password" placeholder="Enter a Admin password" name="adminPassword" required>

	 		<div style="text-align:center; margin-top:2%;">
	 			<label><b>Xml Markup (.xml) : </b></label>
		 		<input type="file" id="xmlMarkup" name="xmlMarkup" accept=".xml" required>  
		 	</div>
	 		<div style="text-align:center; margin-top:2%;">
	 			<label><b>Images (.png): </b></label>
		 		<input type="file" name="images[]" accept=".png" multiple>  
		 	</div>
		 	<div style="text-align:center;">
					<button type="submit" id="uploadContent" name="uploadContent">Upload Content</button>
			</div>
	</form>
</div>

<div id="viewCourseContent">
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

		if(isset($_SESSION['Course'])){
			$course  = $_SESSION['Course'];

			print("<h2>Your Stored Bookmarks: </h2><ul class=\"linkList\">");
			
			$queryResult = mysqli_query($con, "Select Bookmark from bookmarks where Username='" . $user ."'");
			while ($row = mysqli_fetch_assoc($queryResult)){
				
				print("<li><span class=\"link\"><a href=\"#\" onclick=\"\">" . $row["Bookmark"] . "</a></span>");
				print("<input type=\"button\" class=\"editButton\" onClick=\"editLink('" . $row["Bookmark"] . "')\" value=\"Edit\"/>");
				print("<input class=\"deleteButton\" type=\"button\" onClick=\"deleteLink('" . $row["Bookmark"] . "')\" value=\"Delete\"/></li>");
			}
			print("</ul>");	

			print("<div style=\"text-align:center;\"><input class=\"addButton\" type=\"button\" onClick=\"addLink()\"  value=\"Add Bookmark\"\></div> ");
		}
		?>
</div>

</body>
</html>