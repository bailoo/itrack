<?php
  $title1=$_POST['title'];
  //echo "test";

  echo"<div class='help_height1'></div><center><b>".$title1."</b></center>";
?>
<center>
<div class='help_height1'></div>
<table class="help_right_table1" border=0 cellspacing=5 cellpadding=5>
  <tr>
    <td class="help_td1" valign="top">Step1</td>
    <td class="help_td2" valign="top">&#8658;</td>
    <td class="help_td3" valign="top">Login to your account.</td>
  </tr>
  <tr>
    <td class="help_td1" valign="top">Step2</td>
    <td class="help_td2" valign="top">&#8658;</td>
    <td class="help_td3" valign="top">Click on <b>manage link</b> at the top right location of the page.</td>
  </tr>
  <tr>
    <td colspan="3" valign="top"><img src="images/help/heighlight_manage.png" class="img_style"></td> 
  </tr>
   <tr>
    <td class="help_td1" valign="top">Step3</td>
    <td class="help_td2" valign="top">&#8658;</td>
    <td class="help_td3" valign="top">Click on <b>Handling Geofence</b> link at left <b>Tree View Navigation</b>.</td>
  </tr>
   <tr>
    <td class="help_td1" valign="top">Step4</td>
    <td class="help_td2" valign="top">&#8658;</td>
    <td class="help_td3">Click on <b>Add Radio Button</b> in the add <b>Geofence  form</b>.</td>
  </tr>
  <tr>
    <td colspan="3"><img src="images/help/geofence_help_add.png" class="img_style"></td> 
  </tr>
  <tr>
    <td class="help_td1" valign="top">Step5</td>
    <td class="help_td2" valign="top">&#8658;</td>
    <td class="help_td3" valign="top">Add Geofence Form will be opened.</td>
  </tr>
    <tr>
    <td class="help_td1" valign="top">Step6</td>
    <td class="help_td2" valign="top">&#8658;</td>
    <td class="help_td3" valign="top">Click on desired <b>Account Checkbox/Checkboxes</b> in which you want to create geofence in <b> Add Geofence Form</b>.</td>
  </tr>
  <tr>
    <td colspan="3" valign="top"><img src="images/help/geo_account.png" class="img_style"></td> 
  </tr> 
  <tr>
    <td class="help_td1" valign="top">Step7</td>
    <td class="help_td2" valign="top">&#8658;</td>
    <td class="help_td3" valign="top">
		Fill <b>Name Input Field</b> in add Geofence form carefully.You see a <b>Available Not Available</b> message below enter button.
		<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<b>Available : </b> Means you have written new geofence name which is not available in model.You can proceed with this name.
		<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<b>Not Available : </b> Means you have written geofence name which is available in model.You can not proceed with this name.		
	</td>
  </tr>
  <tr>
    <td colspan="3" valign="top"><img src="images/help/geo_name.png" class="img_style"></td> 
  </tr> 
  <tr>
    <td class="help_td1" valign="top">Step8</td>
    <td class="help_td2" valign="top">&#8658;</td>
    <td class="help_td3" valign="top">
		Two options are available for enter coordinates.  
			<b>1.By Map&nbsp;&nbsp;&nbsp;&nbsp;2.By Manual</b>
    </td>
   </tr>
  <tr>
    <td class="help_td3" colspan=3>
			<b>By Map :</b>			
			Click on 
			<b>Read Only Coord Input Field</b> 
			for add geofence coordinates.A 
			<b>Map Pop Up</b> 
			will be opened.There is 
			<b>Four Option Available</b> 
			in this  map pop up.<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<b>A:)  Draw Option : </b> 
			This option is used for draw geofence on map.
			A drowing tool will be shown on map.
			You just left click on desired place of map to plot coordinates by this drawing tool.
			You must encircle the coordinates of geofence.
			<b>You must click on first point again to encircle the geofence.</b>
			Your geofence will be created If there is <b>Red Light Color Visible</b> in geofence area.<br><br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<b>B:)  Clear/Refresh Option :</b>This opiton is used for <b> Clear/Refresh the draw geofence image on map</b>.You can again draw geofence after Clear/Refresh geofence on map.<b>This option is useful when you do some mistake while drawing geofence.</b> 
			<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<b>C:)  OK Option : </b> Ok option works as enter button.Click on
			<b>OK Option</b> 
			after final drawing of geofence to save.
			<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<b>D:)  Close Option : </b>It is used for enabled the background activity.	</td>
  </tr>
  <tr>
    <td colspan="3" valign="top"><img src="images/help/map_geofence_coord.png" class="img_style"></td> 
  </tr>
   <tr>    
    <td class="help_td3" valign="top" colspan=3>		
			<b>2.By Manual:</b> A 
			<b>Geofence Input Field </b>
			will be displayed after click on this option.You just enter the coordinates in a 
			<b>Given Format</b> that is  written in 
			<b>Above Small Brackets.</b> We are giving an example of coordinates format below. <br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			
			<b>Example: </b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			
			<b>(latA, lngA),(latB, lngB): </b>&nbsp;(22.98873816096074, 78.92578125),(22.98873816096074, 78.92578125).<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<b>[ends with first set] : </b>&nbsp;Means Enter last point of coordinates as First point of coordinates.			
	</td>
  </tr>
  <tr>
    <td colspan="3" valign="top"><img src="images/help/geo_coord.png" class="img_style"></td> 
  </tr>
  <tr>   
    <td class="help_td3" valign="top" colspan=3>		
			<P class="note"> 
				Note : Background activity will be disabled when you chose any option for making geofence.It enabled once again when you click on Close Button OR Ok Button 
			</p>
	</td>
  </tr>
 <tr>
    <td class="help_td1" valign="top">Step9</td>
    <td class="help_td2" valign="top">&#8658;</td>
    <td class="help_td3" valign="top">Finally click on Enter button.If there is any alert message has been open.It means there is some wrong entry or blank field.Please correct it and you have done.</td>
  </tr>
  <tr>
    <td class="help_bottom_td1" colspan="3" valign="top">
      <center>You see a message (Geofence Added Successfully). It means Geofence added in model.</center>
    </td> 
  </tr>
  <tr>
    <td class="help_bottom_td2" colspan="3">
      <center>Track Your Geofence Feel Free!</center>
    </td>  
  </tr>
</table>
</center>
