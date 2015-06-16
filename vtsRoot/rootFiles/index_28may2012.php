<?php  
  include_once('src/php/Hierarchy.php');  
  include_once('src/php/util_session_variable.php');
  include_once("src/php/util_php_mysql_connectivity.php");  

  $currentdate=date("Y-m-d");
	list($currentyear,$currentmonth,$currentday)=split("-",$currentdate);  
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
    <input name="width" class="tb1" value="" type="hidden">
    <input name="height" class="tb1" value="" type="hidden">
    <input name="resolution" class="tb1" value="" type="hidden">
  
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
											<table border="0" cellspacing="2" cellpadding="2" class="menu"> 
												<tr>													
													<td>Group ID</td>
													<td>:</td>
													<td><input name="group_id" type="text" size='9'><br></td>
													<td colspan="4" valign='top' align='right'><font size='3' color='FF240C'><b>Track Your Vehicle</b></td>
												</tr>
												<tr>
													<td>User ID</td>
													<td >:</td>
													<td><input name="user_id" type="text" size='17'><br></td>
													<td>Password</td>
													<td>:</td>
													<td><input name="password" value="" type="password" size='17'></td>
													<td><input value="Sign In" type="submit"></td>
												</tr>						
											</table>  
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<table width="100%" bgcolor="#000033" cellspacing=0 cellpadding=0>
									<tr>
										<td align="left" width="25%">
											&nbsp;<a href="search_transporters.php" target="_blank" style="text-decoration:none">&nbsp;<font color= "white" size=2><b>Find Transporters</font></a>
										</td>
										<!--<td align="center" width="20%">
											&nbsp;<blink><a href="http://old.itracksolution.com" target="_blank" style="text-decoration:none">&nbsp;<font color= "white" size=2><b>Old website</font></a></blink>
										</td>-->
										
										<td align="center" width="25%">
											&nbsp;<a href="http://www.iembsys.com/4.0/contactus.php" target="_blank" style="text-decoration:none">&nbsp;<font color= "white" size=2><b>Contact Us</font></a>
										</td>
										
										<td align="center" width="25%">
											&nbsp;<a href="http://www.iembsys.com/4.0/distributors.php" target="_blank" style="text-decoration:none">&nbsp;<font color= "white" size=2><b>Distributors</font></a>
										</td>
										
                    										
										<td align="right" width="25%">
											&nbsp;<font color= "white" size=2> <b>Sales No : (+91-9935551952) &nbsp;</font></a>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<div id="page-wrap">
									<div class="slider-wrap">
										<div id="main-photo-slider" class="csw">
											<div class="panelContainer">							
												<div class="panel" title="Panel 1">
													<div class="wrapper">
														<img src="images/flash_images/vtssolution.png" alt="Device 1"  />  
													</div>
												</div>

												<div class="panel" title="Panel 2">
												<div class="wrapper">
													<img src="images/flash_images/remotemonitorsolution.png" alt="Device 2"  />  
												</div>
											</div>
											<div class="panel" title="Panel 3">
											<div class="wrapper">
												<img src="images/flash_images/substationmonitoring.png" alt="Device 3"  />  
											</div>
										</div>
										<div class="panel" title="Panel 4">
											<div class="wrapper">
												<img src="images/flash_images/wirelessgsmmodem.png" alt="Device 4"  />  
											</div>
										</div>
										<div class="panel" title="Panel 5">
											<div class="wrapper">
												<img src="images/flash_images/itrackdevice.png" alt="Device 5"  />  
											</div>
										</div>
										<div class="panel" title="Panel 6">
											<div class="wrapper">
												<img src="images/flash_images/zigbeebasedsystem.png" alt="Device 6"  />  
											</div>
										</div>
										<div class="panel" title="Panel 7">
											<div class="wrapper">
												<img src="images/flash_images/autoirrigationslide.png" alt="Device 7"  />  
											</div>
										</div>
										<div class="panel" title="Panel 8">
											<div class="wrapper">
												<img src="images/flash_images/gsmmodemslide.png" alt="Device 8"  />  
											</div>
										</div>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<table valign="top" align="center" border="0" cellpadding="0" cellspacing="1" width="100%">


<tbody><tr valign="top">
    
      <td style="margin: 2px;">
	  <div class="allpageheading2">TRANSPORT...</div> 
                 <ol class="allpagesb_menu2">
          <li><img src="images/general/transport.jpg"></li>
                    <li style="margin: 0px;">Vehicle Tracking System</li> 
                   
                    <li style="margin: 0px;">Mine Tracking System</li> 
                   
                    <li style="margin: 0px;">Access Control</li> 
                   
                    <li style="margin: 0px;">Point of Sales</li> 
                  <!-- <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=1&amp;start=0&amp;p_f=0">Vehicle Tracking System</a></li> 
                   
                    <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=1&amp;start=1&amp;p_f=1">Mine Tracking System</a></li> 
                   
                    <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=1&amp;start=2&amp;p_f=2">Access Control</a></li> 
                   
                    <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=1&amp;start=3&amp;p_f=3">Point of Sales</a></li> -->
				   
				   </ol> </td>
                <td style="margin: 2px;"><div class="allpageheading2">ENERGY...</div> 
                 <ol class="allpagesb_menu2">
          <li><img src="images/general/electricity.jpg"></li> 
					<li style="margin: 0px;">Sub Station Monitoring System</li> 
                   
                    <li style="margin: 0px;">Automated Meter Reading</li> 
                   
                    <li style="margin: 0px;">Energy Meter</li> 
                   <!-- <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=2&amp;start=0&amp;p_f=0">Sub Station Monitoring System</a></li> 
                   
                    <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=2&amp;start=1&amp;p_f=1">Automated Meter Reading</a></li> 
                   
                    <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=2&amp;start=2&amp;p_f=2">Energy Meter</a></li> -->
                   </ol> </td>
                <td style="margin: 2px;"><div class="allpageheading2">TELECOM...</div>
                 <ol class="allpagesb_menu2">
          <li><img src="images/general/telecom.jpg"></li> 
		  <!-- <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=3&amp;start=0&amp;p_f=0">Auto Mains Failure</a></li> 
                   
                    <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=3&amp;start=1&amp;p_f=1">Remote Monitoring System</a></li> 
                   
                    <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=3&amp;start=2&amp;p_f=2">DC Energy Meter</a></li> 
                   
                    <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=3&amp;start=3&amp;p_f=3">Energy Safety Unit</a></li> 
                   
                    <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=3&amp;start=4&amp;p_f=4">AC Controller</a></li> -->
                    <li style="margin: 0px;"> Auto Mains Failure</li>                    
                    <li style="margin: 0px;">Remote Monitoring System</li>
                    <li style="margin: 0px;"> DC Energy Meter</li>                    
                    <li style="margin: 0px;"> Energy Safety Unit</li>                    
                    <li style="margin: 0px;"> AC Controller</li> 
                   </ol> </td>
                <td style="margin: 2px;"><div class="allpageheading2">RAILWAYS...</div> 
                 <ol class="allpagesb_menu2">
          <li><img src="images/general/railways.jpg"></li>
					<li style="margin: 0px;"> Simran</li>                    
                    <li style="margin: 0px;"> Fog Pass System</li>                    
                    <li style="margin: 0px;"> LC Gate Alarm System</li>                    
                    <li style="margin: 0px;"> Controlled Discharge Toilet System</li>
                    <!--<li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=4&amp;start=0&amp;p_f=0">Simran</a></li> 
                   
                    <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=4&amp;start=1&amp;p_f=1">Fog Pass System</a></li> 
                   
                    <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=4&amp;start=2&amp;p_f=2">LC Gate Alarm System</a></li> 
                   
                    <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=4&amp;start=3&amp;p_f=3">Controlled Discharge Toilet System</a></li> -->
                   </ol> </td>
                <td style="margin: 2px;"><div class="allpageheading2">AGRICULTURE...</div>
                 <ol class="allpagesb_menu2">
          <li><img src="images/general/agriculture.jpg"></li> 
                    <li style="margin: 0px;">Weather Station</li>                    
                    <li style="margin: 0px;">Automatic Irrigation System</li>                    
                    <li style="margin: 0px;">Nano Sensor</li> 
                   <!-- <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=5&amp;start=0&amp;p_f=0">Weather Station</a></li> 
                   
                    <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=5&amp;start=1&amp;p_f=1">Automatic Irrigation System</a></li> 
                   
                    <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=5&amp;start=2&amp;p_f=2">Nano Sensor</a></li> -->
				   </ol> </td>
                <td style="margin: 2px;"><div class="allpageheading2">OTHERS...</div> 
                 <ol class="allpagesb_menu2">
          <li><img src="images/general/device2.png"></li> 
                    <li style="margin: 0px;"> GSM / GPRS Modem</li> 
                   
                    <li style="margin: 0px;"> Zigbee Modem</li> 
                   
                    <li style="margin: 0px;"> GPS Modem</li> 
					<!--<li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=6&amp;start=0&amp;p_f=0">GSM / GPRS Modem</a></li> 
                   
                    <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=6&amp;start=1&amp;p_f=1">Zigbee Modem</a></li> 
                   
                    <li style="margin: 0px;"> <a href="http://www.iembsys.com/4.0/product.php?productcatid=6&amp;start=2&amp;p_f=2">GPS Modem</a></li> -->
                   </ol> </td>
     </tr>
 
</tbody></table>
							</td>
						</tr>					

	<tr>
		<td>
			<table width=100% class="menu" bgcolor="EAEAEA">
				<tr valign=top>
					<td align='center'>				
					<strong>Innovative Embedded Systems provides full service for hardware and firmware design and prototyping for micro controller and embedded systems</strong>
					</td>
				</tr>
				<tr valign=top>
					<td align='right'>				
						<strong>&copy;IESPL All Right Reserved (2005-2011)</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a  href="http://www.iembsys.com"><font color="darkblue"><b>Go To&nbsp;:&nbsp;iembsys.com</font></a></b>&nbsp;
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
