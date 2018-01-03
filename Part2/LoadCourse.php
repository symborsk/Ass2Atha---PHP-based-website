<html>

<?php 
session_start();
define('DB_HOST', 'localhost');
define('DB_NAME', 'sys'); 
define('DB_USER','root'); 
define('DB_PASSWORD','symbor97');
?>

<head>
	<title>
		<?php
		print("Online Course: " . $_SESSION['Course']);
		?>
	</title>
	<link href="../Shared/tma2_style.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div class="courseContent">
		<header>
		   <?php

		   function BuildAssignment($xml, $content){
		   		$xmlObject = simplexml_load_string($xml);
				print_r($xmlObject);
		   }


		   function BuildQuiz($xml, $content){
				$dom = new DOMDocument;
				$dom->loadXML($xml);
				$questions = $dom->getElementsByTagName('MultipleChoice');
				$id = 0;
				
				//Store all the answers in a session variable so we can use it for marking later
				$allAnswers = [];
				
				foreach ($questions as $question) {
    				print_r("<h5>" . question)
				}

			    $i=0;
        		while(is_object($multipleChoiceQuestion = $doc->getElementsByTagName("MultipleChoice")->item($i))){
        			$title = "";
        			$options = [];

        			foreach ($multipleChoiceQuestion->childNodes as $node){
        				if($node->nodeName == "QuestionTitle"){
        					$title = $node->nodeValue;
        				}
        				elseif($node->nodeName == "OptionList"){
        					foreach($node->childNodes as $option){
        						$options[] = $option;
        					}
        				}
        				//This has to be answer because we validate it using schema
        				else{
        					$allAnswers[$id] = $nodeValue
        				}
        			}

        			AddMultipleChoiceQuestion($id, $title, $options);
        			$id++;
        			$i++;
        		}

    		   	$i=0;
				while(is_object($selectionQuestion = $doc->getElementsByTagName("Selection")->item($i))){
        			$title = "";
        			$options = [];
        			$answer = "";
        			foreach ($multipleChoiceQuestion->childNodes as $node){
        				if($node->nodeName == "QuestionTitle"){
        					$title = $node->nodeValue;
        				}
        				elseif($node->nodeName == "OptionList"){
        					foreach($node->childNodes as $option){
        						$options[] = $option;
        					}
        				}
        				//This has to be answer because we validate it using schema
        				else{
        					$allAnswers[$id] = $nodeValue
        				}
        			}

        			AddSelectionQuestion($id, $title, $options);
    				$id++;
        			$i++;
        		}

    		   	$i=0;
				while(is_object($trueFalse = $doc->getElementsByTagName("TrueFalse")->item($i))){
        			$title = "";
        			$options = [];
        			$answer = "";
        			foreach ($trueFalse->childNodes as $node){
        				if($node->nodeName == "QuestionTitle"){
        					$title = $node->nodeValue;
        				}
        				//This has to be answer because we validate it using schema
        				else{
        					$allAnswers[$id] = $nodeValue
        				}
        			}

        			AddTrueFalseQuestion($id, $title, $options);
    				$id++;
        			$i++;
        		}
		   }

		   function BuildLesson($xml, $content){
		   		$xmlObject = simplexml_load_string($xml);
				print_r($xmlObject);
		   }

		   function BuildTitles($Unit, $Type){
		   	print_r("<h1> Class: " .  $_SESSION['Course'] . "</h1>" );
		   	print_r("<h2> Unit: " .  $Unit . "</h2>" );
		   	print_r("<h4> Material: " .  $Type . "</h4>");
		   	print_r("<hr style=\"margin:0px\">");
		   }

		   	$course = $_SESSION['Course'];
		   	$courseId = $_GET['ID'];

			$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);
			if (!$con ) {
			    die('Cannot Connect to MYSQL Server');
			}

			$db_selected = mysqli_select_db($con, DB_NAME);
			if (!$db_selected) {
			    die ('Cant connect to db');
			}

			$result = mysqli_query($con, "Select * from courses where ID=" . $courseId . " and courseName='" . $course ."'");
			if(mysqli_num_rows($result) == 1){
				$row = mysqli_fetch_assoc($result);

				$courseUnit = $row["CourseUnit"];
				$type = $row['Type'];
				$XmlMarkup = $row['XmlMarkup'];
				$ContentPath = $row['ContentPath'];

				BuildTitles($courseUnit, $type);

				switch($type){

					case "Assignment":
						BuildAssignment($XmlMarkup, $ContentPath);
						break;
					case "Quiz":
						BuildQuiz($XmlMarkup, $ContentPath);
						break;
					case "Lesson":
						BuildLesson($XmlMarkup, $ContentPath);
						break;
				}

			}
			else{
				die("You do not have access to this course.");
			}

		   ?>
		</header>

		<hr style="margin:0px"> 

	</div>
</body>