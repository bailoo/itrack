<?php
include_once("util_session_variable.php");
?>

<html>
<head>
<script type="text/javascript" language="javascript" src="js/datetimepicker_sd.js"></script>
<script type="text/javascript" language="javascript" src="js/datetimepicker.js"></script>


<script type="text/javascript" language="javascript">

  function action_io(obj)
  {
		//alert(obj);
    var imei = document.getElementById('imei').value; 
		var date1 = document.getElementById('date1').value; 
    var date2 = document.getElementById('date2').value; 
    var rec = document.getElementById('record_len').value; 
    
    if(imei=="")
    {
      alert("Please Enter correct Imei No");
      return false;
    }
    if(date1=="")
    {
      alert("Please Enter Start Time");
      return false;      
    }
    if(date2=="")
    {
      alert("Please Enter End Time");
      return false;
    }
    obj.submit();      
  }
</script>

</head>

<body>

<?php

if($account)
{		                                                                                            
  echo '		
  <center>
    <table border=0 width = 100% cellspacing=2 cellpadding=0>
  		<tr>
  			<td height=10 class="report_heading" align="center"><strong>VTS <font color=red>IO</font> LOG</strong></td>
  		</tr>
  	</table>    
    
  <form  method="post" name="thisform" action="action_io_log.php">		
		<br><br>    		
        <table align="center">    		 
    			<tr>
    				<td>
    				<fieldset style="width:50%;height:150px;">
    					<legend>
    						<font color="darkgreen" size="2">
    							Enter Input parameters
    						</font>
    					</legend>
        			<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
        				<TR>
        					<TD>
        						
              <table align="center" style="border-width:0pt;" border="0">
              <tr>
              <td><font color=darkgreen size="2">Enter IMEINo:</font></td>
              <td align="left" colspan="4"><input type="text" id="imei" name="imei" /></td>
              </tr>
              </table>
                  
						<div STYLE=" height:400px; overflow:auto">';

							date_default_timezone_set('Asia/Calcutta');
							$StartDate=date('Y/m/d 00:00:00');
							$EndDate=date('Y/m/d H:i:s');						
						  
              echo'
								<br>
							 <table width="470" align="center" border="0">
										<TR valign="top" cellspacing="0" cellpadding="0" align="left"> 
											<TD  height="24" width="70">
												<font size="2">Date From</font></td>
												<td align="center"><input type="text" id="date1" name="date1" value="'.$StartDate.'" size="16" maxlength="19">
												</td>
												<td><a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)><img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a>
											</TD>

											<TD  height="24" width="90" align="right">
												<font size="2">Date To</font></td>
												<td align="center"><input type="text" id="date2" name="date2" value="'.$EndDate.'" size="16" maxlength="19">
												</td>
												<td>
												<a href=javascript:NewCal("date2","yyyymmdd",true,24)><img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a>
											</TD>
										<input type="hidden" name="date_id" value="1">		
										</TR>																	
									</table>

                <br><table align="center"><tr>
							<td class="text"><input type="radio" name="record_len" id="record_len" value="10" >last 10</td> 
							<td class="text"><input type="radio" name="record_len" id="record_len" value="30" checked>last 30</td>                                                
    						<td class="text">&nbsp;<input type="radio" name="record_len" id="record_len" value="100">last 100</td>                                              
    						<td class="text">&nbsp;<input type="radio" name="record_len" id="record_len" value="all">all</td>
                </tr>
                </table></center>					
					    </fieldset>
							<br><br><br>
							<table border=0 align="center">						
							<tr>
								<input type="hidden" id="account_id_local1" value="'.$account_id_local1.'">
                <td class="text" align="left"><input type="button" value="Submit" onclick="javascript:action_io(this.form);"></td>
							</tr>
							</table>
							</form>
						</div>
					 </TD>
				 </TR>
			</TABLE>
		</td>
	</tr>
</TABLE>


<div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>';
}
else
{
	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=index.php\">";
}
  
echo "</body>";

?>
