<?php

echo '
<html>
<head>
<script type="text/javascript" language="javascript" src="../datetimepicker.js"></script>
</head>

<body>

<form action="action_xml_check.php" method="POST">
<div align="right"><a href="../logout.php" style="text-decoration:none;"><font color=green size=3><strong>Logout</strong></font></a></div> 
<center>
   
  <font FACE=Arial color=green><strong>XML FILE CHECK ON SERVER</strong></font><br><br>
  
  <table border="1" rules="all">
    <tr>
    <td><font color=red>Enter IMEINo:</font></td>
    <td><input type="text" name="imei" /></td>
    </tr>
            
    <tr>
    <tr>
    <td colspan="2"></td></tr>
    <td><font color=red>DateFrom :</font></td>
    <td>
    
    <input type="text" id="date1" name="datefrom" size="10" maxlength="19">

		<a href=javascript:NewCal("date1","yyyymmdd",false,24)>
			<img src="../cal.gif" width="16" height="16" border="0" alt="Pick a date">
		</a>
    
    </td>
    
    <td><font color=red>DateTo :</font></td>
      <td>
      
      <input type="text" id="date2" name="dateto" size="10" maxlength="19">
  
  		<a href=javascript:NewCal("date2","yyyymmdd",false,24)>
  			<img src="../cal.gif" width="16" height="16" border="0" alt="Pick a date">
  		</a>
    
    </td>    
    </tr>
        
  </table>   
   
  <br><br><input type="submit" value="Check File Existence" /><br><br>
  <a href="../home.php" style="text-decoration:none;"><font color=blue size=3><strong>Home</strong></font>&nbsp;
</center>

</form> 

</body>
</html>
';
?>
