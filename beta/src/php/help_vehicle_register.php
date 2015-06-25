<?php
  $title1=$_POST['title'];
  //echo "test";
   $valign="valign='top'";
  echo"<div class='height1'></div><center><b>".$title1."</b></center>";
?>
<center>
<div class='height1'></div>
<table class="help_right_table1" border=0 cellspacing=5 cellpadding=5>
  <tr>
    <td class="help_td1" <?php echo $valign; ?>>Step1</td>
    <td class="help_td2" <?php echo $valign; ?>>&#8658;</td>
    <td class="help_td3" <?php echo $valign; ?>>Login to your account.</td>
  </tr>
  <tr>
    <td class="help_td1" <?php echo $valign; ?>>Step2</td>
    <td class="help_td2" <?php echo $valign; ?>>&#8658;</td>
    <td class="help_td3" <?php echo $valign; ?>>Click on manage link at the top right location of the page.</td>
  </tr>
  <tr>
    <td colspan="3" <?php echo $valign; ?>><img src="images/help/heighlight_manage.png" class="img_style"></td> 
  </tr>
   <tr>
    <td class="help_td1" <?php echo $valign; ?>>Step3</td>
    <td class="help_td2" <?php echo $valign; ?>>&#8658;</td>
    <td class="help_td3" <?php echo $valign; ?>>Click on vehicle link at left tree view navigation.</td>
  </tr>
   <tr>
    <td class="help_td1" <?php echo $valign; ?>>Step4</td>
    <td class="help_td2" <?php echo $valign; ?>>&#8658;</td>
    <td class="help_td3" <?php echo $valign; ?>>Click on Register radio button in vehicle form.</td>
  </tr>
  <tr>
    <td colspan="3" <?php echo $valign; ?>><img src="images/help/help_vehicle_register.png" class="help_img_style"></td> 
  </tr>  
  <tr>
    <td class="help_td1" <?php echo $valign; ?>>Step5</td>
    <td class="help_td2" <?php echo $valign; ?>>&#8658;</td>
    <td class="help_td3" <?php echo $valign; ?> colspan=3>Click on account radio button for register vehicle.</td>
  </tr>
  <tr>
    <td colspan="3" <?php echo $valign; ?>><img src="images/help/help_vehicle_edit1.png" class="help_img_style"></td> 
  </tr>
  <tr>
    <td class="help_td1" <?php echo $valign; ?>>Step6</td>
    <td class="help_td2" <?php echo $valign; ?>>&#8658;</td>
    <td class="help_td3" <?php echo $valign; ?> colspan=3>Click on one vehicle display option among three options.</td>
  </tr>
  <tr>
    <td colspan="3" <?php echo $valign; ?>>
		<table width="100%" border="0" style="border-top:1px solid #FF0000; border-bottom:1px solid #FF0000; background-color:#999999">
		  <tr>
			<td>
				<font color="white">
					<b>All:</b> Click on this option if you want to display all vehicle for selected account.<br>
					<b>Select By Vehicle Type:</b> Click on this option if you want to display all vehicle according to vehicle types.If you select this option, vehicle types will be displayed, you will select one or more vehicle type for showing the vehicle of these vehicle type/types.<br>
					<b>Select By Vehicle Tag:</b>  Click on this option if you want to display all vehicle according to vehicle tag.If you select this option, vehicle tag will be displayed, you will select one or more vehicle tag for showing the vehicle of these vehicle tag/tags. <br>
				</font>
			</td>
		  </tr>
		</table>
	</td> 
  </tr>
  <tr>
    <td colspan="3" <?php echo $valign; ?>><img src="images/help/help_vehicle_edit2.png" class="help_img_style"></td> 
  </tr>
  <tr>
    <td colspan="3" <?php echo $valign; ?>><img src="images/help/help_vehicle_edit3.png" class="help_img_style"></td> 
  </tr>
  <tr>
    <td colspan="3" <?php echo $valign; ?>><img src="images/help/help_vehicle_edit4.png" class="help_img_style"></td> 
  </tr>
  <tr>
    <td class="help_td1" <?php echo $valign; ?>>Step7</td>
    <td class="help_td2" <?php echo $valign; ?>>&#8658;</td>
    <td class="help_td3" <?php echo $valign; ?>>Click on Enter button.If there is any alert message display then full fill the requirement of this alert message.After it another form will be displayed.</td>
  </tr>
  <tr>
    <td class="help_td1" <?php echo $valign; ?>>Step8</td>
    <td class="help_td2" <?php echo $valign; ?>>&#8658;</td>
    <td class="help_td3" <?php echo $valign; ?>>Click on those IMEI no and vehicle which you want to register with each other.Both will be displayed in the above text box.</td>
  </tr>
   <tr>
    <td colspan="3" <?php echo $valign; ?>><img src="images/help/help_vehicle_register1.png" class="help_img_style"></td> 
  </tr>
  <tr>
    <td colspan="3" <?php echo $valign; ?>>
		<table width="100%" border="0" style="border-top:1px solid #FF0000; border-bottom:1px solid #FF0000; background-color:#999999">
		  <tr>
			<td>
				<font color="white">
					If there is no device assigned to this particular account it is showing No Device Found.The same conditin is also applied for vehicle.If device and vehicle both are not assigned to this particular account then device part display No Divce Found and vehicle part display No Vehicle Found.<br>
				</font>
			</td>
		  </tr>
		</table>
	</td> 
  </tr> 
  <tr>
    <td class="help_td1" <?php echo $valign; ?>>Step9</td>
    <td class="help_td2" <?php echo $valign; ?>>&#8658;</td>
    <td class="help_td3" <?php echo $valign; ?>>Click on Register button.If there is any alert message display then full fill the requirement of this alert message and you have done.</td>
  </tr>
  <tr>
    <td class="help_bottom_td1" colspan="3" <?php echo $valign; ?>>
      <center>You see a message (Device And Vehicle Assigned Successfully) After click on Register button. It means vehicle has been Registered successfully.</center>
    </td> 
  </tr>
  <tr>
    <td class="help_bottom_td2" colspan="3" <?php echo $valign; ?>>
      <center>Track Your Vehicle Feel Free!</center>
    </td>  
  </tr>
</table>
</center>
