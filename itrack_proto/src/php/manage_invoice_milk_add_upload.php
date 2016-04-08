<html>  
  <head> 
	 <script language="javascript" src="../js/drag.js"></script>
	<script language="javascript" src="../js/tablefilter.js"></script>

	<link rel="StyleSheet" href="../css/module_hide_show_div.css?<?php echo time();?>">	
	
	<link rel="stylesheet" href="../css/calendar.css">
	<script type="text/javascript" src="../js/menu.js"></script>
	<script language="javascript" src="../js/calendar_us.js"></script> 
	<link rel="stylesheet" href="../css/search_style.css?<?php echo time();?>">
	<script language="javascript" src="../js/datetimepicker.js"></script>
	<script language="javascript" src="../js/datetimepicker_sd.js"></script>
	<script type="text/javascript" src="../js/jquery.js"></script> 
	<script language="javascript" src="../js/ajax.js?<?php echo time();?>"></script>
	<script type="text/javascript" src="../js/jquery-1.3.2.js"></script> 
	<script type="text/javascript" src="../js/markers.js?<?php echo time();?>"></script>
	<script language="javascript" src="../js/manage.js?<?php echo time();?>"></script>
	<script language="javascript" src="../js/user_type.js?<?php echo time();?>"></script> 
	<script language="javascript" src="../js/manage_transporters.js?<?php echo time();?>"></script>
	<script language="javascript" src="../js/manage.js?<?php echo time();?>"></script>
     <?php 
		include_once('Hierarchy.php');  
		include_once('util_session_variable.php');
		include_once("util_php_mysql_connectivity.php");	
		include_once("util_account_detail.php");
    	
    	
     ?>  
		<style type="text/css">

.divm {
 position:absolute;
 top:50%;
 right:50%;
 width:100px;
}

@media print 
	{
		.noprint
		{
			display: none;
		}
	}
	@media screen
	{ 
		.noscreen
		{ 
			display: none; 
		} 
	}

  .normal1 { background-color: #F8F8FF }
  .highlight1 { background-color: #C6DEFF }

  .normal2 { background-color: #FFFDF9 }
  .highlight2 { background-color: #C6DEFF }
 </style>
  </head>
  
<body class="body_part" topmargin="0"  onload="javascript:resize('home')"  onresize="javascript:resize('home')">
 <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">   <!--MAIN FRAME TABLE 1 OPENS-->       
    <tr height="20px" valign="top">       
       <td>     
         <?php
			/*if($user_type=='substation'){
				include('module_frame_substation_header.php');
			}
			else if($user_type=='raw_milk'){
				include('module_frame_raw_milk_header.php');
			}
			else if($user_type=='plant_raw_milk'){
				include('module_frame_substation_header.php');
			}
			else if($user_type=='hindalco_invoice'){
				include('module_frame_raw_milk_header.php');
			}*/
			
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
									include('manage_add_raw_milk_admintype.php');
									/*if($user_type=='substation'){
										include('manage_route_vehicle_substation_assignment.php');
									}
									else if($user_type=='raw_milk'){
										include('manage_add_raw_milk_usertype.php');
									}
									else if($user_type=='plant_raw_milk'){
										include('manage_edit_raw_milk_prev.php');
									}
									else if($user_type=='hindalco_invoice'){
										include('manage_add_hindalco_invoice_usertype.php');
									}*/
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