var currentLinkSelected;

function popLoginForm(){
	document.getElementById('modalDialogLogin').style.display='block';
}

function editLink(link){
	document.getElementById('modalDialogEdit').style.display='block';
	document.getElementById('currentLinkEdit').innerHTML = "Changing link: " + link ;
	currentLinkSelected = link;
}

function addLinkCloseDialog(){
	
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
	     			addLinkCloseDialog();
	     		}	     
	     	}
	 	}
     }

 	request.send();
}

function addLinkCloseDialog(){
	var priorEdit = currentLinkSelected;
	var newLink = document.getElementById('newLinkEdit').value;

	document.getElementById('modalDialogEdit').style.display='none';
    request = new XMLHttpRequest();    
    request.open("GET", "Validate.php?priorEdit="+priorEdit+"&postEdit=" + newLink, true);
    request.onreadystatechange = function(){

	    if (request.readyState == 4) 
	    {
	     	if(request.status != 200){
	     		alert(request.responseText);
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