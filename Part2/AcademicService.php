<!DOCTYPE html>

<?php 
session_start();
define('DB_HOST', 'localhost');
define('DB_NAME', 'sys'); 
define('DB_USER','root'); 
define('DB_PASSWORD','symbor97');
?>

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
		  <li><a href ="#" onclick="lastLoadedCourseShow()">Course Material</a></li>
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
	<div class="errorText">
		<?php
			if(isset($_SESSION['Error'])){
				print($_SESSION['Error']);
    			unset($_SESSION['Error']);
			}
		?>
	</div>
</div>

<div id="courseSearch" class="searchBar"
		<?php
		if(isset($_GET["ShowCourseMaterial"])){
			print(" style=\"display:none;\"");
		}
	?>
>
	<h1 id="MainTitle">Search For Assignments/Quizzes/Lessons</h1>
	<h2 id="SubTitle">You will need to know the password for the course in order to access it!</h2>
	<form method='post' class="embeddedForm" action='CourseHelper.php' >
		<label><b>Course Selection</b></label>
		<select class="courseList" id="courseList" name ="courseListSelection">		
			<?php
			$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);
			if (!$con ) {
			    die('Cannot Connect to MYSQL Server');
			}

			$db_selected = mysqli_select_db($con, DB_NAME);
			if (!$db_selected) {
			    die ('Cant connect to db');
			}

			$result = mysqli_query($con, "Select CourseName from course_login");
			while ($row = mysqli_fetch_assoc($result)){
				print("<option value=\"" . $row["CourseName"] . "\">". $row["CourseName"] . "</option>" );
			}

			?>
		</select>
		<label><b>Course Password</b></label>
		<input type="password" placeholder="Enter a student course password" name="coursePassword" required> 
	 	<div style="text-align:center;">
			<button type="submit" name="selectCourse">Show Content</button>
		</div>
	</form>
</div>

<div id="uploadCourse" class="uploadCourse">
	
	<h1 id="MainTitle">Upload a xml markup and any images that go with it</h1>
	<h2 id="SubTitle">You will need an admin and student password when uploading. If creating be sure to remember your admin and student password.</h2>

	<form method='post' class="embeddedForm" action='uploadCourse.php' enctype="multipart/form-data" >
			<label><b>Course Name: </b></label>
			<input type="text" placeholder="Select existing or enter new course name" name="courseName" list="courses"  required>
				<datalist id="courses">
					<?php
						$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);
						if (!$con ) {
						    die('Cannot Connect to MYSQL Server');
						}

						$db_selected = mysqli_select_db($con, DB_NAME);
						if (!$db_selected) {
						    die ('Cant connect to db');
						}

						$result = mysqli_query($con, "Select CourseName from course_login");
						while ($row = mysqli_fetch_assoc($result)){
							print("<option value=\"" . $row["CourseName"] . "\">". $row["CourseName"] . "</option>" );
						}
					?>
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
		 	<div style="text-align:center;">
					<button type="submit" id="createCourse" name="createCourse">Create Course</button>
			</div>
	</form>
</div>

<div id="viewCourseContent" class="viewCourseContent"
	<?php
		if(isset($_GET["ShowCourseMaterial"])){
			print(" style=\"display:block;\"");
		}
	?>>
	
	<h1 id="MainTitle">Last Course Loaded</h1>
	<h2 id="SubTitle">"All the materials of the last course you loaded"</h2>
	<?php
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
		print("<h2> All material for: " . $course . "</h2><ul class=\"linkList\">");

		$queryResult = mysqli_query($con, "Select ID, Type, CourseUnit from courses where CourseName='" . $course ."'");
		while ($row = mysqli_fetch_assoc($queryResult)){
			$link = "LoadCourse.php?ID=" . $row["ID"];
			$courseDisplay = $row["CourseUnit"] . " - " . $row["Type"];

			print("<li><a target=\"_blank\" href=\"". $link . "\">" . $courseDisplay . "</a></li>");
		}
		print("</ul>");	
	}
	?>
</div>

</body>
</html>