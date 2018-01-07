

<?php 
	function convertArraysToCSV($post){
		$tempArray = $post;
		foreach($post as $key=>$value){
			if(is_array($value)){
				$stringOfArray = implode(",", $value);
				$tempArray[$key]=$stringOfArray;
			}
		}

		return $tempArray;
	}

	session_start();

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
	
	if(isset($_POST['selectCourse'])){
		$courseName = $_POST['courseListSelection'];
		$coursePassword = $_POST['coursePassword'];
		print("Select * from course_login where courseName='" . $courseName . "' and coursePassword='" . $coursePassword . "'");
	 	$loginResult = mysqli_query($con, "Select * from course_login where courseName='" . $courseName . "' and coursePassword='" . $coursePassword . "'");

 		if(mysqli_num_rows($loginResult) == 1){
			$_SESSION['Course'] = $courseName;
			header("Location: AcademicService.php?ShowCourseMaterial=true");
		}
	
		else{
			$_SESSION['Error'] = "Invalid course password!";
			header("Location: AcademicService.php");
		}	
	}
	elseif(isset($_POST["SubmitQuiz"])){
		$postConverted = convertArraysToCSV($_POST);
		$count = count($_SESSION['Answers']);
		$score = $count;

		foreach($_SESSION['Answers'] as $key=>$value){
			
			if($value != $postConverted[$key]){
				print_r("<p>question: " . $key . " Wrong! <br> Your Answer: " .  $postConverted[$key]  . "<br> Actual Answer:<b> " .$value .  "</b></p>" );
				$score--;
			}
	
		}

		print_r("<p>" . $score . " out of " . $count . "</p>");
	}

?>
