<?php
	include_once('src/php/util_session_variable.php');
	include_once('src/php/util_php_mysql_connectivity.php');
	$DEBUG=0;
	$parameter_type="order";	

  echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
  include_once('manage_order.php');

  echo'
  <center>
    <div style="height:10px"> </div>
 
      <TABLE align="center">  
				<tr>
					<td valign="top">Reference Type</td>
          <td>:</td>
          <td colspan=2>';

		echo'<select name="ref_type_id" id="ref_type_id" onchange="javascript:show_hierarchy_vendor();">
              <option value="select">Select</option>
              <option value="0">None</option>
              <option value="1">Distributor</option>
              <option value="2">Sales Person</option>
            </select>';

         echo'</td>
        </tr>          					    
        
				<tr>
					<td valign="top">Reference ID</td>
          <td>:</td>
          <td colspan=2>';

            echo'<select name="ref_id" id="ref_id">';
              echo '<option value="0">Select</option>';
               for($i=0;$i<$size;$i++)
               {
                  echo '<option value="'.$vendor_id[$i].'">'.$vendor_name[$i].'</option>';
               }                             
            echo'</select>';

         echo'</td>
        </tr>
        
        <tr>
					<td valign="top">Select Vendor</td>
          <td>:</td>
          <td colspan=2>';
        
            echo'<select name="vendor_id" id="vendor_id">';
               echo '<option value="0">Select</option>';
               for($i=0;$i<$size;$i++)
               {
                  echo '<option value="'.$vendor_id[$i].'">'.$vendor_name[$i].'</option>';
               }
            echo'</select>';

         echo'</td>
        </tr>              
                  
        </TABLE>         
 
      <div style="height:10px"> </div>    
      <div id="order_area" style="display:none">
      <table width="100%" border=0>
        <tr>
          <td width="36%">&nbsp;</td>
          <td>
            <table class="manage_interface" cellspacing="2" cellpadding="2">         					         
					<tr>
						<td valign="top">Order Item</td>
            <td>:</td>
            <td colspan=2>
						  <input type="text" id="order_item">
           </td>
          </tr>
          
					<tr>
						<td valign="top">Order Quantity</td>
            <td>:</td>
            <td colspan=2>          
					   <input type="text" id="order_qty" size="15">
            </td>
          </tr>
                    
					<tr>
						<td valign="top">Unit Price</td>
            <td>:</td>
            <td colspan=2>            
					   <input type="text" id="unit_price" size="15">          
           &nbsp;<em>rupees</em></td>
          </tr>
          
					<tr>
						<td valign="top">Any other cost</td>
            <td>:</td>
            <td colspan=2>          
					   <input type="text" id="any_other_cost" size="15">
            &nbsp;<em>rupees</em></td>
          </tr>
          
					<tr>
						<td valign="top">Discount</td>
            <td>:</td>
            <td colspan=2>
					   <input type="text" id="discount" size="15">
           </td>
          </tr>
          
					<tr>
						<td valign="top">Total Price</td>
            <td>:</td>
            <td colspan=2>
					   <input type="text" id="total_price" size="15">
           &nbsp;<em>rupees</em></td>
          </tr>
                    
					<tr>
						<td valign="top">Vendor ID</td>
            <td>:</td>
            <td colspan=2>
            <div id="vendor_id"></div>
            </td>
          </tr>                   
          
					<tr>
						<td valign="top">Reference ID</td>
            <td>:</td>
            <td colspan=2>
            <div id="ref_id"></div>
            </td>
          </tr>                   
          
					<tr>
						<td valign="top">Reference Type ID</td>
            <td>:</td>
            <td colspan=2>
              <div id="ref_type_id"></div>
            </td>
          </tr>                    
              <tr>
                <td colspan="3">
                <div style="height:10px"> </div>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   <input type="button" id="enter_button" value="Update" onclick="javascript:action_manage_order(\'edit\')"/>
                  &nbsp;<input type="button" value="Delete" onclick="javascript:action_manage_order(\'delete\')"/>
                </td>
              </tr>
          </table>
          </td>
          </tr>
          </table>
    </div>
</center>';

  include_once('availability_message_div.php');
    
  ?>