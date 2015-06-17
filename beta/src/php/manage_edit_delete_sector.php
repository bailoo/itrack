<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$DEBUG=0;
	$parameter_type="sector";
	echo "edit##";
	$common_id1=$_POST['common_id'];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	//echo "coomon_id=".$common_id1;	
	//common_id
?>
<br> 
<table border="0" class="manage_interface" cellspacing="2" cellpadding="2">
    <tr>
        <td>&nbsp;Select Sector&nbsp;:&nbsp;
          <select name="sector_id" id="sector_id" onchange="manage_show_sector_coord(manage1);">
          <option value="select">Select</option>';
          <?php				
				$data=getDetailAllSector($common_id1,$DbConnection);            							
				foreach($data as $dt)
				{
					$sector_id=$dt['sector_id'];
					$sector_name=$dt['sector_name'];					
					echo'<option value='.$sector_id.'>'.$sector_name.'</option>';
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
                <td>Geo Name</td>
                <td>:</td>
                <td><input type="text" name="sector_name" id="sector_name" onkeyup="manage_availability(this.value, 'sector')" onmouseup="manage_availability(this.value, 'sector')" onchange="manage_availability(this.value, 'sector')"></td>                                
             </tr> 
             <tr>                          
                <td>Geo Coord</td>
                <td>:</td>
                <td><textarea readonly="readonly" style="width:350px;height:60px" name="sector_coord" id="sector_coord" onclick="javascript:showCoordinateInterface('sector');"></textarea>                             
                    <!--&nbsp;<a href="javascript:showCoordinateInterface(\'edit_sector\');">Edit</a>--></td>                                
             </tr>
            </table>
          </div>
    </td>                                
   </tr>                               
  <tr>
  	<td colspan="3" align="center"><input type="button" id="enter_button" value="Update" onclick="javascript:action_manage_sector('edit')"/>&nbsp;<input type="button" value="Delete" onclick="javascript:action_manage_sector('delete')"/></td>
  </tr>
  </table>
  
  <?php 
    include_once('availability_message_div.php');
  ?>