<?php
include_once("../util_session_variable.php");
?>

<html>
<head> 
<script type="text/javascript" language="javascript" src="../datetimepicker.js"></script>
<meta charset="utf-8">
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">

 <script>
$(function() {
$( "#datepicker1" ).datepicker();
});

$(function() {
$( "#datepicker2" ).datepicker();
});

</script>

<script type="text/javascript" language="javascript">

  function action_sms_detail(obj)
  {
		//alert(obj);
    var imei = obj.imei.value; 
	var vname = obj.vname.value;
	var date1 = obj.date1.value; 
	var date2 = obj.date2.value; 	
		
    var poststr = "imei=" +imei+
						"&vname=" +vname+
						"&date1_input=" +date1+
						"&date2_input=" +date2;
		//alert("poststr="+poststr);
		makePOSTRequest('action_sms_detail.php', poststr);    
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
      //alert("status="+http_request.readyState);
	  if (http_request.readyState == 4) 
      {
         //alert("status="+http_request.readyState);
		 if (http_request.status == 200) 
         {
            result = http_request.responseText;
            //alert("res="+result)
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
  <center>

    <font FACE=Arial color=green><strong>SMS DETAIL -www.itracksolution.co.in</strong></font><br><br>
    
    <table border="1" rules="all">
      <tr>
        <td><input type="radio" name="input" id="r1" value="1" onclick="document.getElementById(\'imei\').disabled=false;document.getElementById(\'vname\').value=\'\';document.getElementById(\'vname\').disabled=true;"></td>
        <td><font color=green>Enter IMEINo:</font></td>
        <td><input type="text" name="imei" id="imei" onclick="document.getElementById(\'r1\').checked=true;document.getElementById(\'imei\').disabled=false;document.getElementById(\'vname\').value=\'\';document.getElementById(\'vname\').disabled=true;"/></td>
      </tr>
      
      <tr>
        <td><input type="radio" name="input" id="r2" value="2" onclick="document.getElementById(\'vname\').disabled=false;document.getElementById(\'imei\').value=\'\';document.getElementById(\'imei\').disabled=true;"></td>
        <td><font color=green>Vehicle Name:</font></td>
        <td><input type="text" name="vname" id="vname" onclick="document.getElementById(\'r2\').checked=true;document.getElementById(\'vname\').disabled=false;document.getElementById(\'imei\').value=\'\';document.getElementById(\'imei\').disabled=true;"/></td>
      </tr>  
    </table>   
	<br>
	<table border="1" rules="all">
	<!--<tr>
		<td><strong>DateFrom:</strong></td>
		<td><input type="text" id="datepicker1" name="date1"></td>
		<td></td>
		<td><strong>DateTo:</strong></td>
		<td><input type="text" id="datepicker2" name="date2"></td>		
	</tr>-->
	
	<tr><td colspan="2"></td></tr>
    <tr>
	<td><font color=blue>DateFrom :</font></td>
    <td>
    <a href=javascript:NewCal("date1","yyyymmdd",false,24)>
			<input type="text" id="date1" name="date1" size="10" maxlength="19">
			<img src="../cal.gif" width="16" height="16" border="0" alt="Pick a date">
		</a>
		&nbsp;
    </td>
	
	<td><font color=blue>DateTo :</font></td>
    <td>
		<a href=javascript:NewCal("date2","yyyymmdd",false,24)>
			<input type="text" id="date2" name="date2" size="10" maxlength="19">		
			<img src="../cal.gif" width="16" height="16" border="0" alt="Pick a date">
		</a>
		&nbsp;		
    </td>	
    </tr>
	</table>
    <br><br><input type="button" value="GET DETAIL" onclick="javascript:action_sms_detail(this.form);"/>
    <br><br><a href="../home.php" style="text-decoration:none;"><font color=blue size=3><strong>Home</strong></font>&nbsp;
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