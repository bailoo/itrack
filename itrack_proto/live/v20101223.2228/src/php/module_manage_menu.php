<table border='0' width="100%" height="100%" cellspacing="0" cellpadding="0">
  <tr class="mb1">
      <td>
          <?php include('module_logo.php');?> 
      </td>
    </tr>
  <tr  class="mb2">
    <td valign="top">
       <div style="overflow-x:hidden;overflow-y:auto;" id="leftMenu_2"> 
          <table border='0' width="100%" cellspacing="0" cellpadding="0">		           
              <?php                 
                echo'<tr>
                	<td> 
                    <table class="menu" width="100%" border="0" bgcolor="grey" cellspacing="1" cellpadding="1">
                    	<tr>
                    		<td><strong>&nbsp;Manage</strong></td>
                    	</tr>
                    </table>
                  </td>
                </tr>
                
                <tr>
                	<td> 
                    <table>
                    	<tr>
                    		<td><strong></strong></td>
                    	</tr>
                    </table>
                  </td>
                </tr>';              
                 include('module_manage_account.php');
                 include('module_manage_device.php');  
                 include('module_manage_vehicle.php');
                 include('module_manage_device_sale.php');
                 include('module_manage_assign.php');          
                 include('module_manage_deassign.php');                             
                 include('module_manage_landmark.php');  
                 include('module_manage_geofence.php');
                 include('module_manage_route.php');  
                  
              ?>  		          
          </table>
      </div>	
   </td>
  </tr>
  <tr  class="mb3">
      <td>
          <?php include('module_copyright.php');?>
      </td>
  </tr>
</table>					
  	    

		  