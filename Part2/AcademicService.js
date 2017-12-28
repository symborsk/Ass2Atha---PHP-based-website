
function courseLoadShow(){
	document.getElementById('courseSearch').style.display = 'block';
	document.getElementById('uploadCourse').style.display = 'none';
	document.getElementById('viewCourseContent').style.display = 'none';

	document.getElementById('MainTitle').innerHTML = "Search For Assignments/Quizzes/Lessons";
	document.getElementById('SubTitle').innerHTML = "You will need to know the password for the course in order to access it!";
}

function lastLoadedCourseShow(){
	document.getElementById('courseSearch').style.display = 'none';
	document.getElementById('uploadCourse').style.display = 'none';
	document.getElementById('viewCourseContent').style.display = 'block';

	document.getElementById('MainTitle').innerHTML = "Last Course Loaded";
	document.getElementById('SubTitle').innerHTML = "All the materials of the last course you loaded";
}

function uploadContentShow(){
	document.getElementById('courseSearch').style.display = 'none';
	document.getElementById('uploadCourse').style.display = 'block';
	document.getElementById('viewCourseContent').style.display = 'none';

	document.getElementById('MainTitle').innerHTML = "Upload a xml markup and any image that go with it";
	document.getElementById('SubTitle').innerHTML = "You will need an admin and student password when uploading or creating a new course.";
}
