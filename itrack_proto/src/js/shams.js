 ////////////////// HTTP POST REQUEST ///////////////////////////////////////////
  function show_manage_interfaces(type, option)   // option = add_feature
  {
      makePOSTRequest('src/php/' + type+ '_'+ option + '.php', '');   
  }
  
   var http_request11 = false;
   function makePOSTRequest1(url, parameters) 
   {
      http_request1 = false;
      if (window.XMLHttpRequest) 
      { 
         http_request1 = new XMLHttpRequest();
         if (http_request1.overrideMimeType)
         {
         	// set type accordingly to anticipated content type
            //http_request1.overrideMimeType('text/xml');
            http_request1.overrideMimeType('text/html');
         }
      }
      else if (window.ActiveXObject) 
      { // IE
         try 
         {
            http_request1 = new ActiveXObject("Msxml2.XMLHTTP");
         }
         catch (e) 
         {
            try 
            {
               http_request1 = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e)
            {}
         }
      }
      if (!http_request1) 
      {
         alert('Cannot create XMLHTTP instance');
         return false;
      }
      
      http_request1.onreadystatechange = alertContents_1;
      http_request1.open('POST', url, true);
      http_request1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      http_request1.setRequestHeader("Content-length", parameters.length);
      http_request1.setRequestHeader("Connection", "close");
      http_request1.send(parameters);
   }
 
   function alertContents_1()
   {
      if (http_request1.readyState == 4) 
      {
         if (http_request1.status == 200) 
         {
            result = http_request1.responseText;         
            var result1=result.split(":");
            //alert(result1[1])
            if(result1[0]=="success")
            {
              document.getElementById('available_message').innerHTML =result1[1];
              document.getElementById("enter_button").disabled=true;
            }          
            else if(result1[0]=="failure")
            {         
              document.getElementById('available_message').innerHTML =result1[1];
              document.getElementById("enter_button").disabled=false; 
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
  
 
