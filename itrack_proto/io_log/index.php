<?php
echo'

<html>
<head> 
<LINK href="css/fieldset.css" rel="stylesheet" type="text/css">
</head>


<body>

<form action="login.php" method="POST">

<center>

    <table border=0 width = 100% cellspacing=2 cellpadding=0>
  		<tr>
  			<td height=10 class="report_heading" align="center"><strong>INNOVATIVE VEHICLE TRACKING SYSTEMS</strong></td>
  		</tr>
  	</table>    
  
  <div style="height:20%"></div>
  
  <fieldset>
  <legend><strong>VTS <font color=red>IO</font> LOG</strong></legend>
    
  <font color=green><strong>Enter Password to Login :</strong></font><br><br>
  
  <table style="border-width:1px;" border="1" rules="all">
    <tr>            
      <td><input type="password" name="password" id="password" size="20"/></td>
    </tr>
  </table>   
   
  <br><input type="submit" value="Submit"/>   
  
</fieldset>
</center>

</form> 


</body>
</html>
';
?>