<html>
<head>

<script type="text/javascript">     

   var http_request = false;
   function makePOSTRequestLocation(url, parameters) 
   {
      //alert("IN POST REQ");
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
      
      http_request.onreadystatechange = display_variable;
      http_request.open('POST', url, true);
      http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      http_request.setRequestHeader("Content-length", parameters.length);
      http_request.setRequestHeader("Connection", "close");
      http_request.send(parameters);
  }
  
  function save_variable()
  {    
    var js_variable = "js_variable=hello";
    alert(js_variable);
    //makePOSTRequestLocation('C:\\Program Files/Apache Software Foundation/Apache2.2/htdocs/test_cookie/get_js_location_variable.php', js_variable); 
    makePOSTRequestMap('src/php/get_js_location_variable.php', js_variable); 
    //makePOSTRequestMap('src/php/select_station.php', poststr);    
  }
  
  function display_variable()
  {
    //alert("IN alert CNT");
    if (http_request.readyState == 4) 
    {
       if (http_request.status == 200) 
       {
          result = http_request.responseText;
          alert(result);
       }
    }
  }   
       
</script>

</head>

<body onload="javascript:save_variable();">

<?php


?>

</body>
</html>