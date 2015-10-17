<?php
include_once("util_session_variable.php");

//echo "account=".$account;
if($account)
{
echo'
<html>
  <head>
  <style type="text/css">
    table, td, th
    {
    border:1px solid green;
    }
    th
    {
    background-color:green;
    color:white;
    }
  </style>
  
  <script language="javascript" type="text/javascript">
  	function changeColor(color, ID) {
  		document.getElementById(ID).bgColor = "#" + color;
  	}
  	
	function redirect(option)
	{
		if(option==1)
		{
			window.location.href="vehicle_detail_beta/index.php";
		}
		else if(option==2)
		{
			window.location.href="get_group_beta/index.php";
		}      
		else if(option==3)
		{
			window.location.href="vehicle_detail_old/index.php";
		}  
		else if(option==4)
		{
			window.location.href="xml_check/index.php";
		}
		else if(option==5)
		{
			window.location.href="avg_io/index.php";
		}          
		else if(option==6)
		{
			window.location.href="xml_download/index.php";
		}
		else if(option==7)
		{
			window.location.href="debug_account/index.php";
		} 
		else if(option==8)
		{
			window.location.href="suspend_continue_account/suspend_account.php";
		} 
		else if(option==9)
		{
			window.location.href="suspend_continue_account/continue_account.php";
		}
		else if(option==10)
		{
			window.location.href="installation_info/index.php";
		}
		else if(option==11)
		{
			window.location.href="sms_detail/index.php";
		}		
	}
  </script>
  
  </head>

  <body>
  <div align="right"><a href="logout.php" style="text-decoration:none;"><font color=green size=3><strong>Logout</strong></font></a> 
  <br>   
    <center><font color=green face="WildWest" size="3"><strong>IESPL VTS UTILITY&nbsp;&nbsp;&nbsp;&nbsp;<font color=green>(www.itracksolution.co.in)</font></strong></font></center> 
    <br>    
    <table align="center" style="font-size:14px;">      
      <tr>
        <td id="cell1" align="center" style="cursor:pointer" onmouseover="changeColor(\'F7BE81\', this.id);" onmouseout="changeColor(\'FFFFFF\', this.id);" onclick="javascript:redirect(1);"><strong><font color="#610B0B">Account/Vehicle/IMEI Detail&nbsp;</font></strong></td>
        <td id="cell8" align="center" style="cursor:pointer" onmouseover="changeColor(\'F7BE81\', this.id);" onmouseout="changeColor(\'FFFFFF\', this.id);" onclick="javascript:redirect(8);"><strong><font color="#610B0B">Suspend Account&nbsp;</font></strong></td>
		<td id="cell9" align="center" style="cursor:pointer" onmouseover="changeColor(\'F7BE81\', this.id);" onmouseout="changeColor(\'FFFFFF\', this.id);" onclick="javascript:redirect(9);"><strong><font color="#610B0B">Continue Account&nbsp;</font></strong></td>
		<!--<td id="cell2" style="cursor:pointer" onmouseover="changeColor(\'F7BE81\', this.id);" onmouseout="changeColor(\'FFFFFF\', this.id);" onclick="javascript:redirect(2);"><strong><font color="#610B0B">Get group&nbsp;</font><font color=green>(BETA)</font></strong></td>        
       <td id="cell3" style="cursor:pointer" onmouseover="changeColor(\'F7BE81\', this.id);" onmouseout="changeColor(\'FFFFFF\', this.id);" onclick="javascript:redirect(3);"><strong><font color="#610B0B">Vehicle detail&nbsp;</font><font color="blue">(OLD)</font></strong></td>-->                
        <td id="cell4" style="cursor:pointer" onmouseover="changeColor(\'F7BE81\', this.id);" onmouseout="changeColor(\'FFFFFF\', this.id);" onclick="javascript:redirect(4);"><strong><font color="#610B0B"><strong>XML Check&nbsp;</strong></font></td>
        <!--<td id="cell5" style="cursor:pointer" onmouseover="changeColor(\'F7BE81\', this.id);" onmouseout="changeColor(\'FFFFFF\', this.id);" onclick="javascript:redirect(5);"><strong><font color="#610B0B"><strong>Avg IO&nbsp;</strong></font></td>-->
        <td id="cell6" style="cursor:pointer" onmouseover="changeColor(\'F7BE81\', this.id);" onmouseout="changeColor(\'FFFFFF\', this.id);" onclick="javascript:redirect(6);"><strong><font color="#610B0B"><strong>Data Download&nbsp;</strong></font></td>
        <!--<td id="cell7" style="cursor:pointer" onmouseover="changeColor(\'F7BE81\', this.id);" onmouseout="changeColor(\'FFFFFF\', this.id);" onclick="javascript:redirect(7);"><strong><font color="#610B0B"><strong>Debug account&nbsp;</strong></font></td>-->
		<td id="cell10" style="cursor:pointer" onmouseover="changeColor(\'F7BE81\', this.id);" onmouseout="changeColor(\'FFFFFF\', this.id);" onclick="javascript:redirect(10);"><strong><font color="#610B0B"><strong>Device History&nbsp;</strong></font></td>
		<td id="cell11" style="cursor:pointer" onmouseover="changeColor(\'F7BE81\', this.id);" onmouseout="changeColor(\'FFFFFF\', this.id);" onclick="javascript:redirect(11);"><strong><font color="#610B0B"><strong>SMS Detail&nbsp;</strong></font></td>
      </tr>     
    </table>  
  </body>
</html>
';
}
else
{
	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=index.php\">";
}  
?>
