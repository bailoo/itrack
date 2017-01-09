<html>  
  <head>    

     <?php       	
    	include('map_js_css.php');
    	include('common_js_css.php');
        
        
        
        include('util_calculate_distance_js.php');			
		echo"<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places'></script>";
		echo'<script type="text/javascript" src="src/js/markerwithlabel.js"></script>';
		include('googleMapApi.php');   
		
		include('manage_js_css.php'); 
		//echo $user_type;
     ?>
     <?php
       /* if($user_type=='raw_milk' || $user_type=='plant_raw_milk')
        {
            ?>
                 <script language="javascript" src="src/js/datetimepicker_sd.js"></script>
                 <script language="javascript" src="src/js/datetimepicker_sdtime.js"></script>
            <?php
        }
        else
        {
          ?>  
            <script language="javascript" src="src/js/datetimepicker.js"></script>
            <script language="javascript" src="src/js/datetimepicker_sd.js"></script>
            <?php
        }*/
?>
  </head>
  
<body class="body_part" topmargin="0"  onload="javascript:resize('home')"  onresize="javascript:resize('home')">
 <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">   <!--MAIN FRAME TABLE 1 OPENS-->       
    <tr height="20px" valign="top">       
       <td>     
         <?php
                if($user_type=='substation'){
                        include('module_frame_substation_header.php');
                }
                else if($user_type=='raw_milk'){
                        include('module_frame_raw_milk_header.php');
                }
                else if($user_type=='plant_raw_milk' || $user_type=='plant_admin'){
                        include('module_frame_substation_header.php');
                }
                else if($user_type=='hindalco_invoice'){
                        include('module_frame_raw_milk_header.php');
                }
		else if($user_type=='plant_gate'){
                        include('module_frame_substation_header.php');
                }	
		 ?>
		</td>   <!-- TABLE DATA 1 CLOSED -->      
    </tr>      
    <tr valign="top">
        <td>
           <table class="mystyle" width="100%">   <!--MAIN FRAME TABLE 2 OPENS-->
                <tr valign="top">
					<td width="100%" bgcolor="white" class="mystyle">
						<div style="overflow-x:hidden;overflow-y:auto;" id="rightMenu"> 

							<div id="bodyspan" style="height:100%;width:100%;">
								<?php 
									if($user_type=='substation'){
										include('manage_route_vehicle_substation_assignment.php');
									}
									else if($user_type=='raw_milk'){
										//include('manage_add_raw_milk_usertype.php')manage_add_raw_milk_usertype_approve;
										include('manage_add_raw_milk_usertype_approve.php');
									}
									else if($user_type=='plant_raw_milk' || $user_type=='plant_admin'){
										include('manage_edit_raw_milk_prev.php');
									}
									else if($user_type=='hindalco_invoice'){
										include('manage_add_hindalco_invoice_usertype.php');
									}
                                                                        else if($user_type=='plant_gate'){
										include('manage_add_raw_milk_usertype_gate.php');
									}
                                                                ?>
							</div>
						</div>
					</td>
					
					<!--</td>-->
              </tr>            
		      </table> <!--MAIN FRAME TABLE 2 CLOSE-->
        </td>
    </tr>
  </table>  <!--MAIN FRAME TABLE 1 CLOSE-->


<!--<td class="mystyle">-->					
  <?php  
	include_once('manage_loading_message.php');	
  ?>	
</body>
            
</html>