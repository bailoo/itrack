<?php

echo '
<html>
<head>
<script type="text/javascript" language="javascript" src="../datetimepicker.js"></script>
</head>

<body>

<form action="action_xml_download.php" method="POST">
<div align="right"><a href="../logout.php" style="text-decoration:none;"><font color=green size=3><strong>Logout</strong></font></a></div> 
<center>
  
  <font FACE=Arial color=green><strong>DOWNLOAD XML FILE</strong></font><br><br>
  
  <table border="1" rules="all">
    <tr>
    <td><font color=red>Enter IMEI No:</font></td>
    <td><input type="text" name="filename" /></td>
    </tr>
            
    <tr>
    <tr><td colspan="2"></td></tr>
    <td><font color=red>Select Date :</font></td>
    <td>
    
    <input type="text" id="date1" name="xmldate" size="10" maxlength="19">

		<a href=javascript:NewCal("date1","yyyymmdd",false,24)>
			<img src="../cal.gif" width="16" height="16" border="0" alt="Pick a date">
		</a>
    
    </td>
    </tr>
    
    <!--<tr>
    <td colspan="2" align="center"><br>
    <font face=arial size=2>
      <input type="radio" name="filetype" value="1">Current
      <input type="radio" name="filetype" value="2" checked>Sorted
    </font></td>
    </tr>-->
        
  </table>   
   
  <br><br><input type="submit" value="Download File" /><br><br>
  <a href="../home.php" style="text-decoration:none;"><font color=blue size=3><strong>Home</strong></font>&nbsp;
  
</center>

</form> 

</body>
</html>
';
?>
