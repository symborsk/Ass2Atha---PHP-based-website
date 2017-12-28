<?php 
session_start();

if (isset($_FILES['xmlMarkup']) && ($_FILES['xmlMarkup']['error'] == UPLOAD_ERR_OK)) {
	$xml = new DOMDocument(); 
	$xml->load($_FILES['xmlMarkup']['tmp_name']);

	libxml_use_internal_errors(true);
	if (!$xml->schemaValidate('Validate\quiz.xsd')) {
    	print '<b>DOMDocument::schemaValidate() Generated Errors!</b>';
	}
	else{
		print '<b>Well formed and valid xml!</b>';
		var_dump($_POST);
	}   
}
else{	
	$_Session['Error'] = "Error Uploading the xml file!";
}

// header("Location: AcademicService.php");

?>