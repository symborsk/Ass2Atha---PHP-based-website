<?php 
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

	$courseName = $_POST['courseName'];
	$coursePassword = $_POST['coursePassword'];
	$adminPassword = $_POST['adminPassword'];
	$courseType = $_POST['docType'];
	$courseUnit = $_POST['courseUnit'];
	$xmlDoc = ValidateVerifyXml($courseType);
	$picDoc = GetPictureUploads();
 	
 	if($xmlDoc == null){
		header("Location: AcademicService.php");
	}
	

	if(CourseExists($courseName, $con)){
		if(isset($_POST['uploadContent'])){
			UploadToExistingCourse($courseName, $coursePassword, $adminPassword, $courseType, $courseUnit, $xmlDoc, $picDoc, $con);
		}
		else{
			$_SESSION['Error'] = "A Course name called that already exists";
		}	
	}
	else{
		if(isset($_POST['createCourse'])){
			CreateCourseAndUpload($courseName, $coursePassword, $adminPassword, $courseType, $courseUnit, $xmlDoc, $picDoc, $con);
		}
		else{
			$_SESSION['Error'] = "No Course with that name exists.";
		}	
	}

	mysqli_close($con);
	header("Location: AcademicService.php");

	function ValidateVerifyXml($type){

		//libxml_use_internal_errors(true);
		$schemaPath = 'Validate\\' . $type . '.xsd';
		if (isset($_FILES['xmlMarkup']) && ($_FILES['xmlMarkup']['error'] == UPLOAD_ERR_OK)) {
			$xml = new DOMDocument(); 
			$xml->load($_FILES['xmlMarkup']['tmp_name']);

			
			if (!$xml->schemaValidate($schemaPath)) {
		    	$_SESSION['Error'] = "Error in validating the schema of type: " . $type;
		    	return null;
			}
			else{
				return $xml;
			}
		}   
		else{	
			$_SESSION['Error'] = "Error Uploading the xml file!";
			return null;
		}
	}

	function GetPictureUploads(){;
		if (isset($_FILES['images'])) {
			$fileCount = count($_FILES['images']['name']);

			if($fileCount != 0){
				return $_FILES['images']['tmp_name'];
			}
			
			return null;
		}
		else{
			return null;
		}
	}

	function CourseExists($name, $connection){
		$result = mysqli_query($connection, "Select * from course_login where courseName ='" . $name . "'");
		if(mysqli_num_rows($result) == 1){
			
			return true;
		}
		else{
			
			return false;
		}
	}

	function UploadToExistingCourse($course, $coursePass, $adminPass, $courseT, $courseUnit, $xmlDoc, $picDoc , $connection){
		$loginResult = mysqli_query($connection, "Select * from course_login where courseName='" . $course . "' and coursePassword='" . $coursePass . "' and adminPassword='" . $adminPass ."'");
		
		if(mysqli_num_rows($loginResult) == 1){
			//Sanitize the inputs
			$contentPath =  mysqli_real_escape_string($connection, str_replace(' ', '',"UserImages/" . $course . "_" . $courseUnit . "_" . $courseT. "/"));
			$xmlTextContent = mysqli_real_escape_string($connection, $xmlDoc->saveXML());  
			
			//if they have any photos to upload
			if($picDoc != null){
				uploadPictures($picDoc, $contentPath);
			}

			$result = mysqli_query($connection, "insert into courses(CourseName,CourseUnit,Type,XmlMarkup,ContentPath) Values('" . $course . "','" . $courseUnit ."','" . $courseT ."','" . $xmlTextContent ."','" . $contentPath ."')");
		}
		
		else{
			$_SESSION['Error'] = "Invalid admin password or course password!";
		}	
	}

	function CreateCourseAndUpload($course, $coursePass, $adminPass, $courseT, $courseUnit, $xmlDoc, $picDoc, $connection){
		$contentPath =  $course . "_" . $courseUnit . "_" . $courseUnit;  
		$xmlTextContent = $xmlDoc->saveXML();
		print ( "insert into courses Values('" . $course . "','" . $courseUnit ."','" . $courseT ."','" . $xmlTextContent ."','" . $contentPath ."')");

		$result = mysqli_query($connection, "insert into course_login values('" . $course . "','" . $coursePass . "','" . $adminPass . "')");

		UploadToExistingCourse($course, $coursePass, $adminPass, $courseT, $courseUnit, $xmlDoc, $picDoc , $connection);
	}

	function uploadPictures($pics, $contentDir){
		mkdir($contentDir);
		foreach ($pics as $key => $error) {
		    if ($error == UPLOAD_ERR_OK) {
		        $tmp_name = $_FILES["images"]["tmp_name"][$key];
		        $name = basename($_FILES["images"]["name"][$key]);
		        move_uploaded_file($tmp_name, $contentDir . "$name");
		    }
		}
	}
?>