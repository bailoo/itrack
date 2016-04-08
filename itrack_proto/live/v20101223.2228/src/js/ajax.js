////////////////// HTTP POST REQUEST ///////////////////////////////////////////
   
   // SHOW OPTIONS 
   function show_option(type, option)
   {
      makePOSTRequest('src/php/' + type + '_' + option + '.php', '');
   }
   //
   
   var http_request = false;
   function makePOSTRequest(url, parameters) 
   {
      http_request = false;
      if (window.XMLHttpRequest) 
      { 
         http_request = new XMLHttpRequest();
         if (http_request.overrideMimeType)
         {
         	// set type accordingly to anticipated content type
            //http_request.overrideMimeType('text/xml');
            http_request.overrideMimeType('text/html');
         }
      }
      else if (window.ActiveXObject) 
      { // IE
         try 
         {
            http_request = new ActiveXObject("Msxml2.XMLHTTP");
         }
         catch (e) 
         {
            try 
            {
               http_request = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e)
            {}
         }
      }
      if (!http_request) 
      {
         alert('Cannot create XMLHTTP instance');
         return false;
      }
      
      http_request.onreadystatechange = alertContents;
      http_request.open('POST', url, true);
      http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      http_request.setRequestHeader("Content-length", parameters.length);
      http_request.setRequestHeader("Connection", "close");
      http_request.send(parameters);
   }
 
   function alertContents()
   {
      if (http_request.readyState == 4) 
      {
         if (http_request.status == 200) 
         {
            result = http_request.responseText;         
            var result1=result.split("##");
            //alert("Rizwan:"+result1[0]);            
            
            if(result1[0]=="success")
            {
              document.getElementById('available_message').style.display='';
              document.getElementById('available_message').innerHTML =result1[1];
              document.getElementById("enter_button").disabled=false;
              document.getElementById("u_d_enter_button").disabled=false;
            }          
            else if(result1[0]=="failure")
            {         
              document.getElementById('available_message').style.display='';
              document.getElementById('available_message').innerHTML =result1[1];
              document.getElementById("enter_button").disabled=true;
              document.getElementById("u_d_enter_button").disabled=true; 
            }
            else if(result1[0]=="manage_geo_coord")
            {
              document.getElementById("coord_area").style.display="";  /////////for enabling coord input type in Existing option
              document.getElementById('edit_geo_name').value=result1[1];
              document.getElementById('edit_geo_coord').value=result1[2];           
            } 
            else if(result1[0]=="manage_route_coord")
            {
              document.getElementById("coord_area").style.display="";  /////////for enabling coord input type in Existing option
              document.getElementById('edit_route_name').value=result1[1];
              document.getElementById('edit_route_coord').value=result1[2];
            } 
            else if(result1[0]=="manage_landmark_coord")
            {
              document.getElementById("coord_area").style.display="";  /////////for enabling coord input type in Existing option
              document.getElementById('edit_landmark_name').value=result1[1];
              document.getElementById('edit_landmark_point').value=result1[2];             
              document.getElementById('edit_zoom_level').value=parseInt(result1[3]);      
            }         
            else if(result1[0]=="add")
            {
              //alert("Rizwan-Res2="+result1[1]);
              //document.getElementById('new').style.display='none';
              document.getElementById('edit_div').style.display ="";
              document.getElementById('edit_div').innerHTML =result1[1];              
            }                       
            else if(result1[0]=="edit")
            {
              //alert("Rizwan-Res2="+result1[1]);
              //document.getElementById('new').style.display='none';
              document.getElementById('edit_div').style.display ="";
              document.getElementById('edit_div').innerHTML =result1[1];              
            }                                    
            else 
            {
             document.getElementById('bodyspan').innerHTML =result;
            }                  
         }
         else 
         {
            alert('There was a problem with the request.');
         }
      }
   }
   
////////////////// HTTP POST REQUEST CLOSED ////////////////////////////////////

////////////////// HTTP GET REQUEST ///////////////////////////////////////////
function getXMLHttp()
{
  var xmlHttp

  try
  {
    //Firefox, Opera 8.0+, Safari
    xmlHttp = new XMLHttpRequest();
  }
  catch(e)
  {
    //Internet Explorer
    try
    {
      xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e)
    {
      try
      {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch(e)
      {
        alert("Your browser does not support AJAX!")
        return false;
      }
    }
  }
  return xmlHttp;
}
////////////////////// HTTP GET REQUEST CLOSED /////////////////////////////////
