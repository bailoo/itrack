<?php
	include_once('src/php/util_session_variable.php');
  include_once('src/php/util_php_mysql_connectivity.php');
	$DEBUG=0;
	$parameter_type="order";
	//echo "edit##";
	//$postPars = array('escalation_id' , 'action_type' , 'local_account_ids' , 'person_name', 'person_mob' , 'other_detail', 'common_id');
	//$postPars = array('order_id' , 'action_type' , 'order_item', 'order_qty' , 'unit_price', 'any_other_cost', 'discount', 'total_price', 'unit_price', 'vendor_id', 'ref_id', 'ref_type_id');
  //include_once('action_post_data.php');
  //$pd = new PostData();
	//$common_id1=$pd->data[common_id];
	
  $action_type1 = $_POST['action_type'];
  
  if($action_type1 == "finalise")
  {
    echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
    //include_once('manage_order.php');
  
    echo'
    <center>
    <br><div style="height:10px" align="center"><strong>Finalise Order</strong></div><br> 
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
      
          echo'<select name="vendor_id" id="vendor_id" onchange="javascript:show_order_id(this.value,\'placed\');">';
             echo '<option value="0">Select</option>';
             for($i=0;$i<$size;$i++)
             {
                echo '<option value="'.$vendor_id[$i].'">'.$vendor_name[$i].'</option>';                
             }
             echo '<option value="1">TataMotors</option>';
          echo'</select>';
 
       echo'</td>
      </tr>
      
      <span id="order_id" style="display:none;"></span>
                                      
    </TABLE>';         
  }
  
  if($action_type1 == "dispatch")
  {
    echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
    //include_once('manage_order.php');
  
    echo'
    <center>
    <br><div style="height:10px" align="center"><strong>Dispatch Order</strong></div><br> 
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
      
          echo'<select name="vendor_id" id="vendor_id" onchange="javascript:show_order_list(this.value);">';
             echo '<option value="0">Select</option>';
             for($i=0;$i<$size;$i++)
             {
                echo '<option value="'.$vendor_id[$i].'">'.$vendor_name[$i].'</option>';                
             }
             echo '<option value="1">TataMotors</option>';
          echo'</select>';

       echo'</td>
      </tr>                                
    </TABLE>';         
  }
  
  if($action_type1 == "delete")
  {
    echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
    //include_once('manage_order.php');
  
    echo'
    <center>
    <br><div style="height:10px" align="center"><strong>Delete Order</strong></div><br> 
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
      
          echo'<select name="vendor_id" id="vendor_id" onchange="javascript:show_order_list(this.value);">';
             echo '<option value="0">Select</option>';
             for($i=0;$i<$size;$i++)
             {
                echo '<option value="'.$vendor_id[$i].'">'.$vendor_name[$i].'</option>';                
             }
             echo '<option value="1">TataMotors</option>';
          echo'</select>';

       echo'</td>
      </tr>                                
    </TABLE>';         
  }
  
  if($action_type1 == "invoice")
  {
    echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
    //include_once('manage_order.php');
  
    echo'
    <center>
    <br><div style="height:10px" align="center"><strong>Generate Invoice</strong></div><br> 
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
      
          echo'<select name="vendor_id" id="vendor_id" onchange="javascript:show_order_list(this.value);">';
             echo '<option value="0">Select</option>';
             for($i=0;$i<$size;$i++)
             {
                echo '<option value="'.$vendor_id[$i].'">'.$vendor_name[$i].'</option>';                
             }
             echo '<option value="1">TataMotors</option>';
          echo'</select>';

       echo'</td>
      </tr>                                
    </TABLE>';         
  }      
  
  echo'
  <div style="height:10px"> </div>    
  <div id="order_area" style="display:none"></div>';  

  echo'</center>';

  include_once('availability_message_div.php');
    
?>