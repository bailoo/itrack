<?php  
  include_once('src/php/Hierarchy.php');  
  include_once('src/php/util_session_variable.php');
  include_once("src/php/util_php_mysql_connectivity.php");  
  
  $currentdate=date("Y-m-d");
	list($currentyear,$currentmonth,$currentday)=split("-",$currentdate);  
	include_once('src/php/manage_js_css.php');
	
	$query = "SELECT DISTINCT state from transporters";
	//echo $query;
	$result = mysql_query($query,$DbConnection);
	$i=0;   
  while($row = mysql_fetch_object($result))
  {
    $state[$i] = $row->state;     
    $i++;
  } 
		
?>
<html>
  <head>
    <?php
      include('src/php/main_frame_part1.php')
    ?> 

    <script type="text/javascript">     
    if (document.addEventListener) 
    {
      document.addEventListener("DOMContentLoaded", init, false);
    }
    </script>

    <script language="javascript" src="src/js/datetimepicker.js"></script>
    <script language="javascript" src="src/js/datetimepicker_sd.js"></script>
    
  </head>

<body onload="javascript:callpageheight();">
  <?php
    if($account_id)
    {
      echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=home.php\">";
    }
    else
    {
  ?>
	<form name="myform" method = "post" action ="login.php" onSubmit="javascript:return index_validate_form(myform)">
		<div id="topmaindiv" class="main" align=center > <!-- for background color --> 
			<div id="topheaderdiv" class="header">
				<div id="myBoxA">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">								  
						<tr> 
							<td colspan="2" valign="top"> 
								<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor="EAEAEA">    
									<tr>
										<td>
											<table>
												<tr>							
													<td align="left">&nbsp;<img src="images/IES1.png" style="border: medium none;" width="55px"></td>						
													<td align="left"><img src="images/companyname3.png" style="border: medium none;">
												</tr>
											</table>
										</td>
										
										<td align="right" valign='top'>
										
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<table width="100%" bgcolor="#000033" cellspacing=0 cellpadding=0>
									<tr>
										<td >
											&nbsp;<span style="color:#FFFFFF"><strong>FIND TRANSPORTERS</strong></span>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
							</td>
						</tr>
						<tr>
							<td align="center">
								<table valign="top" align="center" border="0" cellpadding="0" cellspacing="1" width="100%">
              <tbody>
                <tr>
                <td align="center" class="text"> <br><br>Select State : 
                <select name="state" onchange="javascript:show_city(this.value);">
                 <?php
                    echo '<option value="0" selected>Select</option>'; 
                    for($j=0;$j<$i;$j++)
                    {
                      echo '<option value="'.$state[$j].'">'.$state[$j].'</option>';  
                    }                                                   
                 ?>
                 </select>
                  
                  <span style"display:none;" id="cityspan"> </span> 
                                   
                  <span style"display:none;" id="bodyspan"> </span>
                 
                 </td>
                 </tr>

              </tbody></table>  <br><br>
							</td>
						</tr>					

          	<tr>
          		<td>
          			<table width=100% class="menu" bgcolor="EAEAEA" align="center">
          				<tr valign=top>
          					<td align="center">				
          					<strong>Innovative Embedded Systems provides full service for hardware and firmware design and prototyping for micro controller and embedded systems</strong>
          					</td>
          				</tr>
          				<tr valign=top>
          					<td align="center">				
          						<strong>&copy;IESPL All Right Reserved (2005-2011)</strong>
          						</b>&nbsp;
          					</td>
          				</tr>
          			</table> 
          		</td>
          	</tr>
							
					</table>
				</div>
			</div>
		</div>
	</form>
  <?php
  }
  ?>

<?php
mysql_close($DbConnection);
?>

</body></html>
