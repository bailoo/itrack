<FORM method="GET" name="form1">
<table border='0' width="100%" height="100%" cellspacing="0" cellspacing="0">
    <tr class="left_tr1" valign="top">
      <td>
        <?php include('module_logo.php');  include('user_type_setting.php');?>       
      </td>
    </tr>
    
    <?php 
      include('module_refresh.php'); 
    ?>
        
  <tr class="left_tr2" valign="top">
    <td valign="top">
      <div style="overflow-x:hidden;overflow-y:auto;" id="leftMenu">  
      <!--<form name="thisform">-->
    		<?php 
    		/*	if($mining_user_type==1 && $account_id!=1)
    			{
    				HomeGetGroup($root);    				
    			} */
    		?>    		  
        
        <input type="hidden" name="lat">
    		<input type="hidden" name="lng">
    		<input type="hidden" name="vid2">
    		<input type="hidden" name="last_marker">
    		<input type="hidden" name="pt_for_zoom">
    		<input type="hidden" name="zoom_level">
    		<input type="hidden" name="current_vehicle">
    		<input type="hidden" name="cvflag">
    		<input type="hidden" name="mapcontrol_startvar">
    		<input type="hidden" name="StartDate">
    		<input type="hidden" name="EndDate">
    		<input type="hidden" name="vehicleSerial">
    		<input type="hidden" name="StartDate1">
    		<input type="hidden" name="EndDate1">  		
            
    <!--<input type="hidden" name="last_dateopt"/>
        <input type="hidden" name="track_mode">
        <input type="hidden" name="last_pos_mode">
        <input type="hidden" name="pt_for_zoom">
        <input type="hidden" name="zoom_level">
        <input type="hidden" name="place_tmp">
        <input type="hidden" name="current_vehicle">
        <input type="hidden" name="cvflag">
        <input type="hidden" name="vid2">
        <input type="hidden" name="lat">
        <input type="hidden" name="lng">
        <input type="hidden" name="last_marker">
        <input type="hidden" name="action_marker">	
        <input type="hidden" name="veh_validation">
        <input type="hidden" name="GEarthStatus">	
        <input type="hidden" name="earthmode">					
        <input type="hidden" name="len2"> -->
	      
        <table border='0' width="100%" class='module_left_menu' cellspacing="0" cellpadding="0">
          
          <?php				
              //include('module_refresh.php');
              include('module_live_vehicle_div.php');
              echo '<tr><td height=50px></td></tr>';                            
              //include('module_mouse_action.php');       
          ?>
        </table>               
        	
		  <?php
		    echo'<div id="blackout_1"> </div>
    <div id="divpopup_1">
	 <table border="0" class="main_page" width="100%">
  <tr>
	<td class="manage_interfarce" align="right" colspan="7" >
  <a href="#" onclick="javascript:close_vehicle_display_option()" class="hs3"><img src="images/close.png" type="image" style="border-style:none;"></a>&nbsp;&nbsp;</td> 													
 </tr>	</table>
		<div id="selection_information" style="display:none;"></div>
	</div>';
echo'<div id="blackout_2"> </div>
    <div id="divpopup_2">
	<table border="0" class="module_left_menu" width="100%">
          <tr>
            <td class="manage_interfarce" align="right" colspan="7">
            <a href="#" onclick="javascript:close_portal_vehicle_information()" class="hs3">
            <img src="Images/close.png" type="image" style="border-style:none;"></a>&nbsp;&nbsp;</td> 													
    	  </tr>
	</table>
	<div id="portal_vehicle_information" style="display:none;"></div>        
    </div>
	';
	?>
      </div>
    </td>
  </tr>  
  <tr class="left_tr3" valign="bottom">
      <td valign="bottom">          
          <?php include('module_copyright.php');?>
      </td>
  </tr>
</table>

 </form>

  	
	
		  
	
