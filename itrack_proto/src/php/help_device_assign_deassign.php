<?php
  $title1=$_POST['title'];
  //echo "test";

  echo"<div class='height1'></div><center><b>".$title1."</b></center>";
?>
<center>
<div class='height1'></div>
<table class="help_right_table1" border=0 cellspacing=5 cellpadding=5>
  <tr>
    <td class="help_td1">Step1</td>
    <td class="help_td2">&#8658;</td>
    <td class="help_td3">Login to your account.</td>
  </tr>
  <tr>
    <td class="help_td1">Step2</td>
    <td class="help_td2">&#8658;</td>
    <td class="help_td3">Click on manage link at the top right location of the page.</td>
  </tr>
  <tr>
    <td colspan="3"><img src="images/help/heighlight_manage.png" class="img_style"></td> 
  </tr>
   <tr>
    <td class="help_td1">Step3</td>
    <td class="help_td2">&#8658;</td>
    <td class="help_td3">Click on Device link at left tree view navigation.</td>
  </tr>
 <tr>
    <td class="help_td1">Step4</td>
    <td class="help_td2">&#8658;</td>
    <td class="help_td3">Click on Assignment/De-assignment radio button in Device form.</td>
  </tr>
	 <tr>
    <td colspan="3"><img src="images/help/device_assign.png" class="help_img_style"></td> 
  </tr>



 <tr>
    <td class="help_td1">Step5</td>
    <td class="help_td2">&#8658;</td>
    <td class="help_td3">Click on Device IMEI number radio button which you want to Assign/De-Assign.</td>
  </tr>
 <tr>
    <td colspan="3"><img src="images/help/device_imei.png" class="help_img_style"></td> 
  </tr>


   <tr>
    <td class="help_td1">Step6</td>
    <td class="help_td2">&#8658;</td>
    <td class="help_td3">Click on one account radio button in which you want to create account in the opened form.</td>
  </tr>

<tr>
    <td colspan="3" <?php echo $valign; ?>><img src="images/help/device_account.png" class="help_img_style"></td> 
  </tr>
  <tr>
    <td colspan="3" <?php echo $valign; ?>>
		<table width="100%" border="0" style="border-top:1px solid #FF0000; border-bottom:1px solid #FF0000; background-color:#999999">
		  <tr>
			<td><font color="white"><b>Note:</b>If child account is selected then parent account will also be selected by default.</font></td>
		  </tr>
		</table>
	</td> 
  </tr>
 
    <td class="help_td1">Step7</td>
    <td class="help_td2">&#8658;</td>
    <td class="help_td3">Click on Enter button. If there is any alert message display then full fills the requirement of   this alert message and you have done.</td>
  </tr>
   
  <tr>
    <td class="help_bottom_td1" colspan="3">
      <center>You see a message (Device Assigned/Deassigned action performed successfully) After click on Enter button. It Means Device IMEI Number is assigned successfully.</center>
    </td> 
  </tr>
  <tr>
    <td class="help_bottom_td2" colspan="3">
      <center>Track Your Device IMEI Number Feel Free!</center>
    </td>  
  </tr>
</table>
</center>
