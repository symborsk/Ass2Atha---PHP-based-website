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

		   function AddFigureExplanationItem($head, $img, $expl, $contentPath){
		   		$imgPath =$contentPath . rawurlencode($img); 
		   		print_r("<div class=figureExplanation>");
		   		print_r("<img src=" . $imgPath . ">");
		   		print_r("<h5>" . $head . "</h5>");
		   		print_r("<p>" . $expl . "</p></div>");
		   }

		   function AddListItem($head, $items, $expl){
		   		print_r("<div class=tutList>");
		   		print_r("<h5>" . $head ."</h5> <ul>");

		   		foreach($items as &$item){
		   			print_r("<li>" . $item->textContent . "</li>");
		   		}

		   		print_r ("</ul><aside>" . $expl ."</aside></div>");	
		   }

		   function AddFigureCaptionItem($imgTitle, $expl, $contentPath){
   		 		$imgPath = $contentPath . rawurlencode($imgTitle); 
   		   		
   		   		print_r("<div class=figureCaption>");
   		   		print_r("<img src=" . $imgPath . ">");
   		   		print_r("<figcaption>" . $expl . "</figcaption></div>");
		   }

		   function AddMultipleChoiceQuestionItem($questionID, $title,  $options){
   		   		print_r("<div class=MultipleChoice id=\"" . $questionID . "\">");
   		   		print_r("<p><b>" . $title ." (SELECT ONE) </b></p> <ul class=\"Answers\">");
	   			foreach ($options as &$option){	 
	   				print_r("<input type=\"radio\" name=\"" . (string)$questionID . "\" value=\"" . $option . "\">" . $option . "<br>");
	   			}

	   			print_r("</ul></div>");
		   }

		   function AddSelectionQuestionItem($questionID, $title,  $options){
		   		print_r("<div class=Selection id=\"" . (string)$questionID . "\">");
   		   		print_r("<p><b>" . $title ." (SELECT ALL THAT APPLY) </b></p> <ul class=\"Answers\">");
   		   		foreach ($options as &$option){	 
   		   			print_r("<input type=\"checkbox\" name=\"" . (string)$questionID . "[]\" value = \"" . (string)$option . "\">" . (string)$option . "<br>");
   		   		}

   		   		print_r("</ul></div>");
   		   	}

		   function AddTrueFalseQuestionItem($questionID, $title){
   				
   				print_r("<div class=TrueFalse id=\"" . (string)$questionID . "\">");
   		   		print_r("<p><b>" . $title ." (SELECT ALL THAT APPLY) </b></p> <ul class=\"Answers\">");
   		   		print_r("<input type=\"radio\" name=\"" . (string)$questionID  . "\" " . "value=\"True\">True<br>");
   		   		print_r("<input type=\"radio\" name=\"" . (string)$questionID  . "\" " . "value=\"False\">False<br>");
	   			print_r("</ul></div>");
		   }

		   function AddAssignmentGroupingItem($head, $body){
		   		print_r("<div class=AssignmentGroup>");
		   		print_r("<h5>" . $head . "</h5>");
		   		print_r("<p>". $body . "</p></div>");
		   }


		   function BuildLesson($xml, $content){
		   		$dom = new DOMDocument;
	   			$dom->preserveWhiteSpace = FALSE;
				$dom->loadXML($xml);
				$i = 0;

		 		while(is_object($FigureExplanation = $dom->getElementsByTagName("FigureExplanation")->item($i))){
        			$Header = "";
        			$ImgTitle = "";
        			$Explanation = "";

        			foreach ($FigureExplanation->childNodes as $node){
        				if($node->nodeName == "Header"){
        					$Header = $node->nodeValue;
        				}
        				elseif($node->nodeName == "IMGTitle"){
        					$ImgTitle = $node->nodeValue;
        				}
        				//This must be explanation
        				else{
        					$Explanation = $node->nodeValue;
        				}
        			}

        			AddFigureExplanationItem($Header, $ImgTitle, $Explanation, $content);
        			$i++;
        		}

				$i = 0;
		 		while(is_object($List = $dom->getElementsByTagName("List")->item($i))){
        			$Header = "";
        			$ListItems = [];
        			$Explanation = "";

        			foreach ($List->childNodes as $node){
        				if($node->nodeName == "Header"){
        					$Header = $node->nodeValue;
        				}
        				elseif($node->nodeName == "ListItems"){
        					foreach($node->childNodes as $ListItem){
								$ListItems[] = $ListItem;
        					}
        				}
        				//This must be explanation
        				else{
        					$Explanation = $node->nodeValue;
        				}
        			}

        			AddListItem($Header, $ListItems, $Explanation);
        			$i++;
        		}

				$i = 0;
		 		while(is_object($FigCaption = $dom->getElementsByTagName("FigCaption")->item($i))){
					$ImgTitle = "";
        			$Explanation = "";

        			foreach ($FigCaption->childNodes as $node){
        				if($node->nodeName == "IMGTitle"){
        					$ImgTitle = $node->nodeValue;
        				}
        				//This must be explanation
        				else{
        					$Explanation = $node->nodeValue;
        				}
        			}

        			AddFigureCaptionItem($ImgTitle, $Explanation, $content);
        			$i++;
        		}

		   }


		   function BuildQuiz($xml, $content){
				$dom = new DOMDocument;
				$dom->preserveWhiteSpace = FALSE;
				$dom->loadXML($xml);
				$id = 0;
			    $i=0;

			    unset($_SESSION['Answers']);

				//Store all the answers in a session variable so we can use it for marking later
				$allAnswers = [];
				
				print_r("<form method='post' class=\"Quiz\" action='CourseHelper.php'>");

        		while(is_object($multipleChoiceQuestion = $dom->getElementsByTagName("MultipleChoice")->item($i))){
        			
      				$title = "";
        			$options = [];

        			foreach ($multipleChoiceQuestion->childNodes as $node){

        				if($node->nodeName == "QuestionTitle"){
        					$title = $node->nodeValue;
        				}
        				elseif($node->nodeName == "OptionList"){
        					foreach($node->childNodes as $option){      				
        							$options[] = $option->nodeValue;       					
        					}
        				}
        				//This has to be answer because we validate it using schema
        				else{
        					$allAnswers[$id] = $node->nodeValue;
        				}
        			}

        			AddMultipleChoiceQuestionItem($id, $title, $options);
        			$id++;
        			$i++;
        		}

    		   	$i=0;
				while(is_object($selectionQuestion = $dom->getElementsByTagName("Selection")->item($i))){
        			$title = "";
        			$options = [];
        			$answer = "";
        			foreach ($selectionQuestion->childNodes as $node){
        			
        				if($node->nodeName == "QuestionTitle"){
        					$title = $node->nodeValue;
        				}
        				elseif($node->nodeName == "OptionList"){
        					foreach($node->childNodes as $option){    					
        							$options[] = $option->nodeValue;    							
        					}
        				}
        				//This has to be answer because we validate it using schema
        				else{
        					$allAnswers[$id] = $node->nodeValue;
        				}
        			}

        			AddSelectionQuestionItem($id, $title, $options);
    				$id++;
        			$i++;
        		}

    		   	$i=0;
				while(is_object($trueFalse = $dom->getElementsByTagName("TrueFalse")->item($i))){
        			$title = "";
        			$options = [];
        			$answer = "";
        			foreach ($trueFalse->childNodes as $node){

        				if($node->nodeName == "QuestionTitle"){
        					$title = $node->nodeValue;
        				}
        				//This has to be answer because we validate it using schema
        				else{
        					$allAnswers[$id] = $node->nodeValue;
        				}
        			}

        			AddTrueFalseQuestionItem($id, $title, $options);
    				$id++;
        			$i++;
        		}

        		$_SESSION['Answers'] = $allAnswers;
        		print_r("	<div style=\"text-align:center;\"><button type=\"SubmitQuiz\" name=\"SubmitQuiz\">Submit Quiz</button></div>");
		   }

		   function BuildAssignment($xml, $content){
				$dom = new DOMDocument;
				$dom->preserveWhiteSpace = FALSE;
				$dom->loadXML($xml);
			    $i=0;

        		while(is_object($multipleChoiceQuestion = $dom->getElementsByTagName("Grouping")->item($i))){
        			$Header = "";
        			$Paragraph = [];

        			foreach ($multipleChoiceQuestion->childNodes as $node){
        				if($node->nodeName == "Header"){
        					$tiHeadertle = $node->nodeValue;
        				}
        			
        				//Must be paragraph
        				else{
        					$Paragraph = $node->nodeValue;
        				}
        			}

        			AddAssignmentGroupingItem($Header, $Paragraph);
        			$id++;
        			$i++;
        		}

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
		<hr style=\"margin:0px\">
	</div>
</body>