
// To detect mobile browser

function detectBrowser() {   
var useragent = navigator.userAgent;   
var mainDiv = document.getElementById("mainDiv"); 

	if (useragent.indexOf('iPhone') != -1 || useragent.indexOf('Android') != -1 || useragent.indexof('mini') != -1 ) {     
		mainDiv.style.width = '100%';    
		mainDiv.style.height = '100%';   
	} 
	else {    
		//mainDiv.style.width = '350px';     
		//mainDiv.style.height = '300px'; 
		
		mainDiv.style.width = '100%';     
		mainDiv.style.height = '100%';  
	} 
}

// To detect which div to be displayed according to mobile screen
function getDisplayType() { 

}


// To set page margins

function setMargin(){

}