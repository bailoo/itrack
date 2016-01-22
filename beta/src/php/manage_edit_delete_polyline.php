<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
        //include('coreDb.php');
	$root=$_SESSION['root'];
	$DEBUG=0;
	$parameter_type="polyline";
	echo "edit##";
	$common_id1=$_POST['common_id'];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	//echo "coomon_id=".$common_id1;	
	//common_id
?>
<br> 
<table border="0" class="manage_interface" cellspacing="2" cellpadding="2">
    <tr>
        <td>&nbsp;Polyline/Route Name&nbsp;:&nbsp;
          <select name="polyline_id" id="polyline_id" onchange="show_polyline_coord(manage1);">
          <option value="select">Select</option>';
          <?php
			
			$data=getDetailAllPolyline($common_id1,$DbConnection);            							
			foreach($data as $dt)
			{
				$polyline_id=$dt['polyline_id'];
				$polyline_name=$dt['polyline_name'];
				echo'<option value='.$polyline_id.'>'.$polyline_name.'</option>';
			}
  		   ?>
  		   </select>
      </td>
   </tr>
   <tr>                          
      <td>
         <div id="coord_area" style="display:none">
           <table class="manage_interface">         					         
             <tr>                          
                <td>Polyline/Route Name</td>
                <td>:</td>
                <td>
					<!--<input type="text" name="polyline_name" id="polyline_name" onkeyup="manage_availability(this.value, 'polyline')" onmouseup="manage_availability(this.value, 'polyline')" onchange="manage_availability(this.value, 'polyline')">-->
					<input type="text" name="polyline_name" id="polyline_name" readonly >
				</td>                                
             </tr> 
             <tr>                          
                <td>Polyline/Route Name Coord</td>
                <td>:</td>
                <td><textarea  style="width:350px;height:60px" name="polyline_coord" id="polyline_coord" onclick="javascript:showCoordinateInterface('polyline');"></textarea>                             
                   <!--&nbsp;<a href="javascript:showCoordinateInterface(\'edit_geofencing\');">Edit</a>--></td>                                
             </tr>
            </table>
          </div>
    </td>                                
   </tr>                               
  <tr>
  	<td colspan="3" align="center"><input type="button" id="enter_button" value="Update" onclick="javascript:action_manage_polyline('edit')"/>&nbsp;<input type="button" value="Delete" onclick="javascript:action_manage_polyline('delete')"/></td>
  </tr>
  </table>
  
  <?php 
    include_once('availability_message_polyline_div.php');
  ?>