<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  include_once('lib/VTSFuel.php');     
  $local_group_id=$_POST['group_id'];
  $escalation_id_1=$_POST['escalation_id'];
  $escalation_id_assigned_id_1 = $_POST['escalation_assigned_id'];
  $calibration_id_1=$_POST['calibration_id'];
  $vendor_id_1=$_POST['vendor_id'];
  $order_id_1=$_POST['order_id'];
  $current_status_1 = $_POST['current_status'];
  //echo "<br>vendor_id=".$vendor_id_1." order_id=".$order_id_1." ,current_status=".$current_status_1;
   
  
  if($local_group_id!="")
  {
    $i=0;
    $msg="";
    $query="SELECT vendor_type_id,vendor_type_name FROM vendor_type  WHERE group_id=$local_group_id AND status='1'";
    //echo "query=".$query;    
    $result=mysql_query($query,$DbConnection);
    $row_result=mysql_num_rows($result);		
    if($row_result!=null)
    {
      while($row=mysql_fetch_object($result))
      {									
        $vendor_type_id=$row->vendor_type_id;
        $vendor_type_name=$row->vendor_type_name; 				
        if($i==0)
        {
          $msg=$msg.$vendor_type_id.":".$vendor_type_name;
        }
        else
        {
          $msg=$msg.",".$vendor_type_id.":".$vendor_type_name;
        }
        $i++;          
      }    
    }               
    echo "manage_vendor_type##".$msg;
  }
  
  	else if($escalation_id_1!="")
	{
		$query="SELECT person_name,person_mobile,person_email,other_detail FROM escalation WHERE escalation_id='$escalation_id_1' AND status=1";
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$person_name1=$row->person_name;
		$person_mob1=$row->person_mobile;
		$person_email1=$row->person_email;
		$other_detail1=$row->other_detail;
		// $final_calibration_data = VTSFuel::calibrationDbToDisplay($calibration_data2);
		echo "escalation_detail##".$person_name1."##".$person_mob1."##".$person_email1."##".$other_detail1;
	}
	else if($moto_pickup_delivery_type!="")
	{
		$query="SELECT station_id,station_name FROM station  WHERE user_account_id=$account_id AND status='1'";
		//echo "query=".$query;    
		$result=mysql_query($query,$DbConnection);
		$row_result=mysql_num_rows($result);
		$main_string=mysql_query_result($query);               
		echo"display_combo##".$moto_displaytype_id."##".$main_string;
	}
	
	else if($cla_serial!="")  // cla=clear loaction assignment
	{
		$query="UPDATE schedule_assignment SET status=0 WHERE serial='$cla_serial' AND status=1";		
		$result=mysql_query($query,$DbConnection);
		if($result)
		{
			echo "success_cla##".$cla_vehicle_id."##".$cla_vehicle_name;
		}
		else
		{
			echo "failure_cla##none##";
		}  
	}
  
  else if($calibration_id_1!="")
  {
    $query="SELECT calibration_name,calibration_data FROM calibration WHERE calibration_id='$calibration_id_1'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $calibration_name1=$row->calibration_name;
    $calibration_data=$row->calibration_data;
	$calibration_data_desplay = VTSFuel::calibrationDbToDisplay($calibration_data);
    // $final_calibration_data = VTSFuel::calibrationDbToDisplay($calibration_data2);
    echo"##calibration_detail##".trim($calibration_name1)."##".trim($calibration_data_desplay);
  }
  
  else if($vendor_id_1!="" && $current_status_1!="")
  {      
      if($current_status_1 == "placed")
        $current_status_1 = "1";
      else if($current_status_1 == "finalised")
        $current_status_1 = "2";
      else if($current_status_1 == "dispatched")
        $current_status_1 = "3";               
      
      $query = "SELECT order_id, FROM order_detail WHERE vendor_id='$vendor_id_1' AND current_status='$current_status_1'";
      $result = mysql_result($query, $DbConnection);
      $size=0;
      while($row = mysql_fetch_object)
      {
        $item_order_id[$i] = $row->order_id;
        $item_order_number[$i] = $row->order_number;
        $size++;
      }
      
      echo'
      <tr>
				<td valign="top">Select Order</td>
        <td>:</td>
        <td colspan=2>';
          $all = "all:".$vendor_id_1;
          
          echo'<select name="order_id" id="order_id" onchange="javascript:show_order_list(this.value,\''.$current_status_1.'\');">';
             echo '<option value="select">Select</option>';
             echo '<option value="'.$all.'">All</option>';
             for($i=0;$i<$size;$i++)
             {
                $item_order_id_tmp = "single:".$item_order_id[$i];
                echo '<option value="'.$item_order_id_tmp.'">'.$item_order_number[$i].'</option>';                
             }
             echo '<option value="1">TataMotors</option>';
          echo'</select>';
 
       echo'</td>
      </tr>';                
  }
  
  else if($order_id_1!="" && $current_status_1!="")
  {
    echo "order_list##";
    
    $order_id_2 = explode(':',$order_id_1);   // type:vendor_id:status 
    
    if($order_id_2[0] == "all")
    {
      $vendor_id_1 = $order_id_2[1];
      
      $query="SELECT sno,order_number,order_item,order_qty,unit_price,order_date,any_other_cost,discount,total_price,current_status FROM order_detail ".
      "WHERE order_id IN(SELECT order_id FROM `order` WHERE vendor_id='$vendor_id_1' AND status='$current_status_1')";
    }
    else
    {
      $query="SELECT sno,order_number,order_item,order_qty,unit_price,order_date,any_other_cost,discount,total_price,current_status FROM order_detail ".
      "WHERE order_id IN(SELECT order_id FROM `order` WHERE order_id='$order_id_2[0]' AND status='$current_status_1')";      
    }
    //echo $query."<br>";
    $result = mysql_query($query,$DbConnection);
    $numrows = mysql_num_rows($result);
    echo '<input type="hidden" id="item_count" value="'.$numrows.'">'; 
    
    $counter=1;
    
    echo'
    <TABLE border="1" id="tblSample" align="center" rules="all">      
      
			<tr>
         <td valign="top">SNo</td>
         <td valign="top">Order Number</td>					   
         <td valign="top">Order Item</td>
			   <td valign="top">Order Quauntity</td>
			   <td valign="top">Unit Price &nbsp;<em>(rupees)</em></td>
			   <td valign="top">Enter Any Other Cost</td>
			   <td valign="top">Discount</td>
			   <td valign="top">Total Price&nbsp;<em>(rupees)</em></td>
			   <td valign="top">Status</td>
			   <td valign="top">Delete (click to delete item)</em></td>
      </tr>';    
              
      while($row=mysql_fetch_object($result))
      {
        $order_serial = $row->sno;
        $order_number = $row->order_number;
        $order_item = $row->order_item;
        $order_qty = $row->order_qty;
        $unit_price = $row->unit_price;
        $order_date = $row->order_date;
        $any_other_cost = $row->any_other_cost;
        $discount = $row->discount;
        $total_price = $row->total_price;
        $current_status = $row->current_status;
                                                                                                                                     
        echo'
          <tr id="row'.$counter.'">                                   
            <td valign="top"> '.$counter.' </td>
            
           <td valign="top">					   
             <label>'.$order_number.'</label>
           </td>
                     
           <td valign="top">					   
             <input type="text" id="order_item'.$counter.'" size="15" value="'.$order_item.'" placeholder="Enter Order Item">
             <input type="hidden" id="serial'.$counter.'" value="'.$order_serial.'">
             <input type="hidden" id="status'.$counter.'" value="1">
           </td>
    
          <td valign="top">
    			   <input type="text" id="order_qty'.$counter.'" size="15" value="'.$order_qty.'" placeholder="Enter Order Quantity">
             <input type="hidden" id="order_qty'.$order_serial.'">
           </td>
    
    			<td valign="top">
    			   <input type="text" id="unit_price'.$counter.'" size="15" value="'.$unit_price.'" placeholder="Enter Unit Price">
    			   <input type="hidden" id="unit_price'.$order_serial.'">
           </td>
                  
    			<td valign="top">
    			   <input type="text" id="any_other_cost'.$counter.'" size="15" value="'.$any_other_cost.'" placeholder="Enter Any Other Cost">
    			   <input type="hidden" id="any_other_cost'.$order_serial.'">
           </td>
          
    			<td valign="top">
    			   <input type="text" id="discount'.$counter.'" size="15" value="'.$discount.'" placeholder="Enter Discount">
    			   <input type="hidden" id="discount'.$order_serial.'">
           </td>
         
    			<td valign="top">
    			   <input type="text" id="total_price'.$counter.'" size="15" value="'.$total_price.'" placeholder="Enter Total Price">
    			   <input type="hidden" id="total_price'.$order_serial.'">
           </td>
           
            <td valign="top">					   
             <label>Placed (in progress)</label>
            </td>         
           
           <td>
              <a href="javascript:delete_order_item('.$counter.');"><img src="./images/manage/delete_icon.gif"></a>
           </td>
          </tr>        
        ';
        $counter++;
      }
      
      echo '</TABLE>      
      
      <br>
      <TABLE align="center">
  			<tr>
  				<td colspan="4" align="center"><br><input type="button" id="enter_button" value="Update" onclick="javascript:action_manage_order(\'finalise\')"/>&nbsp;<input type="reset"" value="Clear" /></td>
  			</tr>
  		</TABLE>';             
  } 
  
  function mysql_query_result($query)
	{
		//echo "query=".$query;
		global $DbConnection;
		$result=mysql_query($query,$DbConnection);
		$num_row=mysql_num_rows($result);
		//echo "num_row=".$num_row."<br>";
		$msg="";
		for($i=0;$i<$num_row;$i++)
		{
			$row=mysql_fetch_row($result);
			if($i==($num_row-1))
			{
				$msg=$msg.$row[0].":".$row[1];
			}
			else
			{
				$msg=$msg.$row[0].":".$row[1].",";
			}		
		}
		return $msg;
	}
    
?>
