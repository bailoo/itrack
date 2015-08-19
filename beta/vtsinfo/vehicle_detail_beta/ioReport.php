<?php
include_once("../util_session_variable.php");
?>

<html>
<head> 

<script type="text/javascript" language="javascript">

  function action_vehicle_detail(obj)
  {
		//alert(obj);
    var imei = obj.imei.value; 
		var vname = obj.vname.value; 
		
    var poststr = "imei=" +imei+
						"&vname=" +vname;
		//alert("poststr="+poststr);
		makePOSTRequest('action_vehicle_detail.php', poststr);    
  }
  
  
   var http_request = false;
   function makePOSTRequest(url, parameters) 
   {
      //alert("url="+url);
      http_request = false;
      if (window.XMLHttpRequest) 
      { 
         http_request = new XMLHttpRequest();
         if (http_request.overrideMimeType)
         {
         	//set type accordingly to anticipated content type
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
            //alert(result)
            document.getElementById('bodyspan').innerHTML =result;                 
         }
         else 
         {
            alert('There was a problem with the request.');
         }
      }
   }

</script>

</head>

<?php

if($account)
{
  echo'
  <body>
  
  <form action="action_vehicle_detail.php" method="POST">
  <div align="right"><a href="../logout.php" style="text-decoration:none;"><font color=green size=3><strong>Logout</strong></font></a> 
  <br><br>
  <center>
    
    <br>  
    <font FACE=Arial color=green><strong>IMEI/VEHICLE/ACCOUNT DETAIL -www.itracksolution.co.in</strong></font><br><br>
    
    <table style="border-width:3pt;" border="1">
      <tr>
        <td><input type="radio" name="input" value="1" onclick="document.getElementById(\'imei\').disabled=false;document.getElementById(\'vname\').value=\'\';document.getElementById(\'vname\').disabled=true;"></td>
        <td><font color=red>Enter IMEINo:</font></td>
        <td><input type="text" name="imei" id="imei" /></td>
      </tr>
      
      <tr>
        <td><input type="radio" name="input" value="2" onclick="document.getElementById(\'vname\').disabled=false;document.getElementById(\'imei\').value=\'\';document.getElementById(\'imei\').disabled=true;"></td>
        <td><font color=red>Vehicle Name:</font></td>
        <td><input type="text" name="vname" id="vname" /></td>
      </tr>
          
    </table>   
     
    <br><br><input type="button" value="GET DETAIL" onclick="javascript:action_vehicle_detail(this.form);"/>
     <br><br><a href="../home.php"><strong>Back</strong></a><br>
  </center>
  
  </form> 
  
  <div id="bodyspan"></div>
  
  </body>
  </html>
  ';
}
else
{
	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=../index.php\">";
}  
?>