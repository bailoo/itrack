<?php  
  include_once('src/php/Hierarchy.php');  
  include_once('src/php/util_session_variable.php');
  include_once("src/php/util_php_mysql_connectivity.php");  
  //require_once('src/php/phpmailer/Mail.php');
  //require_once('src/php/phpmailer/Mail/mime.php');
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
								<div id="page-wrap">
									<div class="slider-wrap">
										<div id="main-photo-slider" class="csw">
											<div class="panelContainer">							
												<div class="panel" title="Panel 1">
													<div class="wrapper">
														<img src="images/flash_images/b1.png" alt="Device 1"  />  
													</div>
												</div>

												<div class="panel" title="Panel 2">
												<div class="wrapper">
													<img src="images/flash_images/b2.png" alt="Device 2"  />  
												</div>
											</div>
											<div class="panel" title="Panel 3">
											<div class="wrapper">
												<img src="images/flash_images/b3.png" alt="Device 3"  />  
											</div>
										</div>
										<div class="panel" title="Panel 4">
											<div class="wrapper">
												<img src="images/flash_images/b4.png" alt="Device 4"  />  
											</div>
										</div>
										<div class="panel" title="Panel 5">
											<div class="wrapper">
												<img src="images/flash_images/b6.png" alt="Device 5"  />  
											</div>
										</div>
									  <div class="panel" title="Panel 6">
											<div class="wrapper">
												<img src="images/flash_images/b7.png" alt="Device 6"  />  
											</div>
										</div>
										<div class="panel" title="Panel 7">
											<div class="wrapper">
												<img src="images/flash_images/b8.png" alt="Device 7"  />  
											</div>
										</div>
									</div>
								</div>
							</td>
						</tr>
						<tr>
            <td>	<img src="images/flash_images/linebottom.png" /> </td>
            </tr>
						<tr>
							<td>
								<table valign="top" align="center" border="0" cellpadding="0" cellspacing="1" bgcolor="" width="100%">


<tbody><tr valign="top">
    
      <td style="margin: 2px;" width=600px>
	         <div class="allpageheading2">Ask Query...</div> 
           <ol class="allpagesb_menu2">
           
                <li style="margin: 10px; text-align:justify;"> 
                    <div>     <iframe src='contactform/contactform.php' frameborder='0' width=560px height=200px  align=center allowtransparency='true'></iframe>
                      </div>
                </li> 
               
              
                 <li style="margin: 10px;font-size:12px; font-family:Verdana, Arial, Helvetica, sans-serif; font-weight:bold;font-style:italic;  color:red;text-align:center;">Sales Ph No: +91-9935551952 </li> 
				    </ol> 
        </td>
     
        
        <td>
          <table >
            <tr>
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
            </tr>
          </table>
        </td>
        
        
        <td>
             <table class="allpageheadingItalic1">
                  <tr>
                    <td>&nbsp;</td><td>&nbsp;<br><br><br><br><br></td>
                  </tr>
                  <tr>
                    <td> <img src="images/flash_images/contactus.jpg" width="70px" /></td><td><a href="contactus.php" style="text-decoration:none">Contact us</a></td>
                  </tr>
                  <tr>
                     <td>  <img src="images/flash_images/distributors.jpg" width="50px"/></td><td><a href="distributors.php" style="text-decoration:none">Distributors</a></td>
                  </tr>
                  <tr>
                    <td><img src="images/flash_images/aboutus.jpg"width="60px" /></td><td><a href="index.php" style="text-decoration:none" >Home</a></td>
                  </tr>
              
              </table>
        </td>
        
      </tr>
 
</tbody></table>
							</td>
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
            &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;Visit <a href="http://www.iembsys.co.in" style="text-decoration:none"><b>iembsys.co.in</b></a>&nbsp; &nbsp;&nbsp;&nbsp;
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
