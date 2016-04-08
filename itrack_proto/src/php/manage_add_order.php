<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');  
	$root=$_SESSION['root']; 
	//$postPars = array('order_id' , 'action_type' , 'order_item', 'order_qty' , 'unit_price', 'any_other_cost', 'discount', 'total_price', 'unit_price', 'vendor_id', 'ref_id', 'ref_type_id');
	//include_once('action_post_data.php');
  //$pd = new PostData();
	
  $query1 = "SELECT vendor_detail.vendor_id, vendor_detail.vender_name FROM vendor,vendor_detail,vendor_type WHERE ".
  "vendor.vendor_id = vendor_detail.vendor_id AND vendor.vendor_type_id = vendor_type.vendor_type_id AND vendor_type_id.group_id='$group_id'";
  $result1 = mysql_query($query1,$DbConnection);
  
  $size=0;
  while($row = mysql_fetch_object($result1))
  {
    $vendor_id[$size] = $row->vendor_id;
    $vendor_name[$size] = $row->vendor_name;
    $size++;
  }  
  
  echo'<br><div style="height:10px" align="center"><strong>Place Order</strong></div><br>';   
  echo '<center>
      <form name="manage1">';     
	
  //include_once('manage_order.php');
	//$local_account_ids=explode(",",$pd->data[local_account_ids]);
  //$account_size=sizeof($local_account_ids); 
	//include_once('tree_hierarchy_information.php');
	
	//include_once('manage_checkbox_account.php');
  //echo"</center>"; 
	echo'<div style="height:10px"> </div>
				<!--<table border="0" class="manage_interface" align="center">

        <table>-->            
        
        <input type="button" value="Add" onclick="javascript:addRowToTable();" />
        <input type="button" value="Remove" onclick="javascript:removeRowFromTable();" />                       
        
        <TABLE border="1" id="tblSample" align="center" rules="all">      
          
					<tr>
             <td valign="top">SNo</td>					   
             <td valign="top">Order Item</td>
					   <td valign="top">Order Quauntity</td>
					   <td valign="top">Unit Price &nbsp;<em>(rupees)</em></td>
					   <td valign="top">Enter Any Other Cost</td>
					   <td valign="top">Discount</td>
					   <td valign="top">Total Price&nbsp;<em>(rupees)</em></td>
          </tr>
                    
          <tr>                                   
            <td valign="top"> 1 </td>
            <td valign="top">					   
             <input type="text" id="order_item1" size="15" placeholder="Enter Order Item">
           </td>


          <td valign="top">
					   <input type="text" id="order_qty1" size="15" placeholder="Enter Order Quantity">
           </td>

					<td valign="top">
					   <input type="text" id="unit_price1" size="15" placeholder="Enter Unit Price">
           </td>
          
          
					<td valign="top">
					   <input type="text" id="any_other_cost1" size="15" placeholder="Enter Any Other Cost">
           </td>
          
					<td valign="top">
					   <input type="text" id="discount1" size="15" placeholder="Enter Discount">
           </td>
         
					<td valign="top">
					   <input type="text" id="total_price1" size="15" placeholder="Enter Total Price">
           </td>
          </tr>
        </TABLE>
        <br> 
        
        <center>
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
                    
          </table>        
          
          <table align="center">
					<tr>
						<td colspan="4" align="center"><br><input type="button" id="enter_button" value="Save" onclick="javascript:action_manage_order(\'add\')"/>&nbsp;<input type="reset"" value="Clear" /></td>
					</tr>
				</TABLE>';      
    
	include_once('availability_message_div.php'); 		
  
  echo'</form>
    </center> ';	
?>
