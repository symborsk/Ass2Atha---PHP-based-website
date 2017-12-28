var currentLinkSelected;

function popLoginForm(){
	document.getElementById('modalDialogLogin').style.display='block';
}

function editLink(link){
	document.getElementById('modalDialogEdit').style.display='block';
	document.getElementById('currentLinkEdit').innerHTML = "Changing link: " + link;
	document.getElementById('editErrorText').innerHTML = ""
	currentLinkSelected = link;
}

function addLink(){
	document.getElementById('modalDialogEdit').style.display='block';
	document.getElementById('currentLinkEdit').innerHTML = "Adding Link:";
	document.getElementById('editErrorText').innerHTML =""
	currentLinkSelected = null;
}

function closeEditNew(){
	document.getElementById('modalDialogEdit').style.display='none';
}

function closeLogin(){
	document.getElementById('modalDialogLogin').style.display='none';
}

function logoutUser(){
    request = new XMLHttpRequest();    
    request.open("GET", "Validate.php?logout=true", true);
    request.onreadystatechange = function(){
	    if (request.readyState == 4) 
    	{
	     	if(request.status == 200)
	     	{
	     		location.reload();
	     	}
    	}
    }

    request.send();
}

function ConfirmNewLink(){
	var newLink = document.getElementById('newLinkEdit').value;
	
	isWorkingLink(newLink);
	return false;
}

function isWorkingLink(link){   
    request = new XMLHttpRequest();    
    request.open("GET", "Validate.php?link="+link, true);
    request.onreadystatechange = function(){

	    if (request.readyState == 4) 
	    {
	     	if(request.status == 200){
	     		
	     		if(request.responseText.indexOf("invalid") != -1){
	     			document.getElementById('editErrorText').innerHTML = "Error: Link Does not exist!"
	     		}
	     		else
	     		{
	     			if(currentLinkSelected == null){
	     				addNewLinkCloseDialog();
	     			}
	     			else{
	     				editLinkCloseDialog();
	     			}     			
	     		}	     
	     	}
	 	}
     }

 	request.send();
}

function editLinkCloseDialog(){
	var priorEdit = currentLinkSelected;
	var newLink = document.getElementById('newLinkEdit').value;

	document.getElementById('modalDialogEdit').style.display='none';
    request = new XMLHttpRequest();    
    request.open("GET", "Validate.php?priorEdit="+priorEdit+"&postEdit=" + newLink, true);
    request.onreadystatechange = function(){

	    if (request.readyState == 4) 
	    {
	     	if(request.status != 200){
	     		alert("Error updating database: " + request.responseText);
	     	}
	     	else{
	     		location.reload();
	     	}
	     }
	 }

	 request.send();
}

function addNewLinkCloseDialog(){
	var newLink = document.getElementById('newLinkEdit').value;

	document.getElementById('modalDialogEdit').style.display='none';
    request = new XMLHttpRequest();    
    request.open("GET", "Validate.php?addValue=" + newLink, true);
    request.onreadystatechange = function(){

	    if (request.readyState == 4) 
	    {
	     	if(request.status != 200){
	     		alert("Error updating database: " + request.responseText);
	     	}
	     	else{
	     		location.reload();
	     	}
	     }
	 }

	 request.send();
}

function deleteLink(link){
    request = new XMLHttpRequest();    
    request.open("GET", "Validate.php?deleteValue="+link, true);
    request.onreadystatechange = function(){

	    if (request.readyState == 4) 
	    {
	     	if(request.status != 200){
	     		alert("Error updating database: " + request.responseText);
	     	}
	     	else{
	     		location.reload();
	     	}
	     }
	 }

	 request.send();
}

window.onclick = function(event) {
    
    if (event.target == document.getElementById('modalDialogLogin')) {
        document.getElementById('modalDialogLogin').style.display = "none";
    }

    if(event.target == document.getElementById('modalDialogEdit')){
        document.getElementById('modalDialogEdit').style.display = "none";
    }
}