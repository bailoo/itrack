function resize(obj)
{ 
  if(obj=='home')
  { 
    var dv = document.getElementById("leftMenu");    
    divHeight =  $(window).height();
    dv.style.height = divHeight - 103;
  }
  else  
  { 
    var dv1 = document.getElementById("leftMenu_1");    
    divHeight =  $(window).height();
    dv1.style.height = divHeight - 25;
    
    var dv2 = document.getElementById("leftMenu_2");    
    divHeight =  $(window).height();
    dv2.style.height = divHeight - 103; 
  }         
}


  
function initialize()
{
   document.getElementById("text").style.display="none";
   document.getElementById("map").style.display="";
  if (GBrowserIsCompatible())
  {	
		map = new GMap2(document.getElementById("map"));
		map.setCenter(new GLatLng(22.755920681486405, 78.2666015625), 5);
		map.setUIToDefault();																	  
	} //GBrowserIs compatible closed			
}  // function closed	

  // function closed	
  
function show_report()
{
  document.getElementById("map").style.display="none";
  document.getElementById("text").style.display="";
  var xmlHttp = getXMLHttp();
 
  xmlHttp.onreadystatechange = function()
  {
    if(xmlHttp.readyState == 4)
    {
      show_report_1(xmlHttp.responseText);
    }
  }

  xmlHttp.open("GET", "src/php/module_report_example.php", true);
  xmlHttp.send(null);
}
	
function show_report_1(responce)
{
  	document.getElementById('text').innerHTML=responce;
}


function show_user(user_account)
{
  if(user_account!="select")
  {
    var xmlHttp = getXMLHttp();
   
    xmlHttp.onreadystatechange = function()
    {
      if(xmlHttp.readyState == 4)
      {
        show_user_1(xmlHttp.responseText);
      }
    }
  
    xmlHttp.open("GET", "src/php/module_user.php?user_account_1="+user_account, true);
    xmlHttp.send(null);
  }
  else
  {
    alert("Please select super user");
  }
}

function show_user_1(responce)
{
  remOption(document.getElementById("user_accountid"));
  addOption(document.getElementById("user_accountid"), "select","select");
  
  var showdata = responce;   					
  var strar = showdata.split(":");
	 
	if(strar.length==1)
	{
			alert("No User Found For This Super User");

			document.getElementById("gid").focus();			
			remOption(document.getElementById("user_accountid"));
			addOption(document.getElementById("user_accountid"), "Select","Select");
	}
  else if(strar.length>1)
  {									
		var j=0;
		for( var i=1;i<strar.length;i++)
		{
			j=i+1;							
			addOption(document.getElementById("user_accountid"), strar[j], strar[i]);
			i++;
		}
  }		
}
 
function addOption(selectbox,text,value)
{
  var optn = document.createElement("OPTION");
  optn.text = text;
  optn.value = value; 
  selectbox.options.add(optn);
}		

function remOption(selectbox)
{			
	var i;
	for(i=selectbox.options.length-1;i>=0;i--)
	{
	selectbox.remove(i);
	}
}	