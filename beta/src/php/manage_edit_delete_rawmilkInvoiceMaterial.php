<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('coreDb.php');
	$DEBUG=0;
	$parameter_type="invoiceMaterial";
	echo "edit##";
	
?>
<br> 
<table border="0" class="manage_interface" cellspacing="2" cellpadding="2">
    <tr>
        <td>&nbsp;Material&nbsp;:&nbsp;
          <select name="material_id" id="material_id" onchange="show_invoiceRawMIlkMaterial();">
          <option value="select">Select</option>';
          <?php				
                $data=getAllRawMilkInvoiceMaterial($account_id,$DbConnection);				
				foreach($data as $dt)
				{					
					$code=$dt['code']; 
					$name=$dt['name'];
                                        $sno=$dt['sno']; 
					echo'<option value='.$sno.'>'.$name.'('.$code.')</option>';
				}
  				?>
  		    </select>
      </td>
   </tr>
   <tr>                          
      <td>
         <div id="mat_area" style="display:none">
           <?php
           echo'
             <table class="manage_interface">         					         
            <tr>
                <td>Material Name</td><td>:</td>
                        <td><input type="text" name="material_name" id="material_name" onkeyup="manage_availability(this.value, \'invoice_rawmilk\')" ></td>
                </tr>
                <tr>
                        <td>Material Code</td><td>:</td>
                        <td>
                            <input type="text" name="material_code" id="material_code" readonly>
                             <input type="hidden" name="snoid" id="snoid">
                        </td>	
                </tr>
                <tr>
                        <td colspan="3" align="center"><input type="button" id="enter_button" value="Edit" onclick="javascript:action_manage_invoiceMaterial(\'edit\')"/></td>
                </tr>
            </table>';
           ?>
          </div>
    </td>                                
   </tr>                               
  
  </table>
  <div id="available_message" align="center"></div> 
  