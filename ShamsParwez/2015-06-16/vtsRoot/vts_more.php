<?php  
  include_once('src/php/Hierarchy.php');  
  include_once('src/php/util_session_variable.php');
  include_once("src/php/util_php_mysql_connectivity.php");  

  $currentdate=date("Y-m-d");
	list($currentyear,$currentmonth,$currentday)=split("-",$currentdate);  
	

	
?>
<html>
  <head>
  
  <style type="text/css">
      
      /* attributes of the container element of textbox */
      .loginboxdiv{
      margin:0px;
      height:21px;
      width:146px;
      background:url(images/flash_images/login_bg.gif) no-repeat bottom;
      }
      /* attributes of the input box */
      .loginbox
      {
      background:none;
      border:none;
      width:134px;
      height:15px;
      margin:0;
      padding: 2px 7px 0px 7px;
      font-family:Verdana, Arial, Helvetica, sans-serif;
      font-size:11px;
      }

      
  </style>
  
  
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
	
	
    <input name="width" class="" value="" type="hidden">
    <input name="height" class="" value="" type="hidden">
    <input name="resolution" class="" value="" type="hidden">
  
    <div id="topmaindiv" class="main" align=center > <!-- for background color --> 
			<div id="topheaderdiv" class="header">
				<div id="myBoxA">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">								  
						<tr> 
							<td colspan="2" valign="top"> 
								<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor="#F8F8F8">    
									<tr>
										<td>
											<table>
												<tr>							
													<td align="left">&nbsp;<img src="images/IES1.png" style="border: medium none;" ></td>						
													<td align="left"><img src="images/companyname3.png" style="border: medium none;">
												</tr>
											</table>
										</td>
										
										<td align="right" valign='top'>
											<table border="0" cellspacing="2" cellpadding="2" class="menu"> 
												<tr>													
													<td><font color="#2B3A94"><b>Group ID</b></font></td>
													<td>:</td>
													<td> 
                               <div class="loginboxdiv">
                                <input name="group_id" class="loginbox"  type="text" />
                                </div>
                          </td>
													<td colspan="4" valign='top' align='right'><img src="images/flash_images/itrack-track.png" /></td>
												</tr>
												<tr>
													<td><font color="#2B3A94"><b>User ID</b></font></td>
													<td >:</td>
													<td>
                             <div class="loginboxdiv">
                                <input name="user_id" class="loginbox"  type="text" />
                              </div>
                          </td>
													<td><font color="#2B3A94"><b>Password</b></font></td>
													<td>:</td>
													<td>
                            <div class="loginboxdiv">
                                <input name="password" class="loginbox"  type="password" />
                              </div>
                          </td>
													<td><input value="Sign In" type="submit" ></td>
												</tr>						
											</table>  
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<img src="images/flash_images/lineupper.png" />
							</td>
						</tr>
						
						
						<tr>
							<td>
								<table valign="top" align="center" border="0" cellpadding="0" cellspacing="1" bgcolor="" width="100%">


<tbody><tr valign="top">
    
      <td style="margin: 2px;" width=600px >
	         <div class="allpageheading2">Vehicle Tracking Systems (VTS)</div> 
           <ol class="allpagesb_menu3a">
           
                <li style="margin: 3px; text-align:justify;"> 
                  <div class="allpagearticle">
                                 
                                    <div align=center>
                                    <table class="allpagesb_menu3" >
                          				
                          						<tr>
                          							<td >
                                              <p>Introduction </p>
                                              <p style="text-align:justify;" class="allpagesb_menu3b">
                                                
A VTS combines the installation of an electronic device in a vehicle, or fleet of vehicles, with purpose-designed computer software (itracksolution) to track the vehicle's location, collecting data in the process from the field and deliver it to the base of operation. VTS(itrack-m8) use GPS technology for locating the vehicle . Vehicle information can be viewed on electronic maps via the Internet or itracksolution software.

                                              </p>
                                              <p>Typical Architecture </p>
                                              <p><i>Major constituents of the GPS based tracking are:</i></p>
                                              <p style="text-align:justify;" class="allpagesb_menu3b">
                                                1. GPS tracking device: The iTrack-M8  fits into the vehicle and captures the GPS location information apart from other vehicle information at regular intervals to a central server. The other vehicle information can include fuel amount, engine temperature, altitude, reverse geocoding, door open/close, tire pressure, cut off fuel, turn off ignition, turn on headlight, turn on taillight, battery status, GSM area code/cell code decoded, number of GPS satellites in view, glass open/close, fuel amount, emergency button status, cumulative idling, computed odometer, engine RPM, throttle position, and a lot more. Capability of these devices actually decide the final capability of the whole tracking system.
                                              </p>
                                              <p style="text-align:justify;" class="allpagesb_menu3b">
                                                2. GPS tracking server: The tracking server has three responsibilities: receiving data from the GPS tracking unit, securely storing it, and serving this information on demand to the user.
                                              </p>
                                              <p style="text-align:justify;" class="allpagesb_menu3b">
                                                3. User interface: The itracksolution software provided by Innovative Embedded Systems Pvt Ltd determines how one will be able to access information, view vehicle data, and elicit important details from it.
                                              </p>
                                              <p>Common Uses Worldwide</p>
                                              <p style="text-align:justify;" class="allpagesb_menu3b">
                                                    &#8227;	Stolen vehicle recovery: Both consumer and commercial vehicles is outfitted with GPS units to allow police or Owner to do tracking and recovery.
                                              </p>
                                              <p style="text-align:justify;" class="allpagesb_menu3b">
                                                  &#8227;	Fleet management: When managing a fleet of vehicles, knowing the real-time location of all drivers allows management to meet customer needs more efficiently. Whether it is delivery, service or other multi-vehicle enterprises, drivers now only need a mobile phone with telephony or Internet connection to be inexpensively tracked by and dispatched efficiently.
                                              </p>
                                              <p style="text-align:justify;" class="allpagesb_menu3b">
                                                   &#8227;	Asset tracking: Companies needing to track valuable assets for insurance or other monitoring purposes can now plot the real-time asset location on a map and closely monitor movement and operating status
                                              </p>
                                              <p style="text-align:justify;" class="allpagesb_menu3b">
                                                   &#8227;	Field service management: Companies with a field service workforce for services such as repair or maintenance, must be able to plan field workers’ time, schedule subsequent customer visits and be able to operate these departments efficiently. Vehicle tracking allows companies to quickly locate a 
                                              </p>
                                        </td>
                          						
                          						</tr>
                          									
                          				
                                  </table>           
                                   </div> 
                                  
                                  </div>
                </li> 
               
				    </ol> 
        </td>
     
        
        <td>
          <table  border=0  >
            <tr >
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td style="font-size:18px; font-family:Bodoni MT; font-smooth: always;font-weight:bold;font-style:italic;  color:#FF6347;text-align:center;">Vehicle Tracking System<br><br></td>
            </tr> 
            <tr>
              <td>
              
                <table class="allpageheadingItalic">
                  <tr>
                    <td> &#149;</td><td>GPS/GPRS Based Tracking System</td>
                  </tr>
                  <tr>
                    <td> &#149;</td><td>Alert Notifications</td>
                  </tr>
                  <tr>
                    <td> &#149;</td><td>Water Proof Enclosure</td>
                  </tr>
                  <tr>
                    <td> &#149;</td><td>Internal Memory for data storage</td>
                  </tr>
                  <tr>
                    <td> &#149;</td><td>Distance Calculation</td>
                  </tr>
                  <tr>
                    <td> &#149;</td><td>Track on Mobile</td>
                  </tr>
                  <tr>
                    <td> &#149;</td><td>Built in Antenna</td>
                  </tr>
                  <tr>
                    <td> &#149;</td><td>Over Voltage Protection (Optional)</td>
                  </tr>
                  <tr>
                    <td> &#149;</td><td>Backup Battery (Optional)</td>
                  </tr>
                  <tr>
                    <td> &#149;</td><td>IO's(Optional)(Fuel,Ignition,..)</td>
                  </tr>
                </table>
              </td>
              
              
              <td>
             <table class="allpageheadingItalic1">
                  <tr>
                    <td>&nbsp;</td><td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td> <img src="images/flash_images/contactus.jpg" width="70px" /></td><td><a href="contactus.php" style="text-decoration:none">Contact us</a></td>
                  </tr>
                  <tr>
                     <td>  <img src="images/flash_images/distributors.jpg" width="50px"/></td><td><a href="distributors.php" style="text-decoration:none">Distributors</a></td>
                  </tr>
                  <tr>
                    <td><img src="images/flash_images/askquery.jpg"width="70px" /></td><td><a href="querycontact.php" style="text-decoration:none" >Ask Query</a></td>
                  </tr>
                  <tr>
                    <td><img src="images/flash_images/aboutus.jpg"width="60px" /></td><td><a href="index.php" style="text-decoration:none" >Home</a></td>
                  </tr>
              
              </table>
        </td>
        
              
            </tr>
            
            <tr>
            <td colspan=2>
            <br><br>
                <img src="images/flash_images/vtswork.png" width="400px">
            </td>
            </tr>
          </table>
        </td>
          
        
        
      </tr>
 
</tbody></table>
							</td>
						</tr>	
            
            </tr>
						<tr>
            <td>	<img src="images/flash_images/linebottom.png" /> </td>
            </tr>				

	<tr>
		<td>
			<table width=100% class="menu" bgcolor="EAEAEA">
				<tr valign=top>
					<td align='center' class="allpageheadingfooter">				
					Innovative Embedded Systems provides full service for hardware and firmware design and prototyping for micro controller and embedded systems
					</td>
				</tr>
				<tr valign=top>
					<td align='right' class="allpageheadingfooter">				
						&copy;IESPL All Right Reserved (2005-2012)&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
            &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;Visit <a href="http://www.iembsys.co.in" style="text-decoration:none"><b>iembsys.com</b></a>&nbsp; &nbsp;&nbsp;&nbsp;
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
