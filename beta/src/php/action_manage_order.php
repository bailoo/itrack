<?php
	include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
 
  //$postPars = array('order_id' , 'action_type' , 'order_item', 'order_qty' , 'unit_price', 'any_other_cost', 'discount', 'total_price', 'unit_price', 'vendor_id', 'ref_id', 'ref_type_id');
  //include_once('action_post_data.php');
  //$pd = new PostData();   
  $DEBUG=0;   
 // $setgetpostvalue_obj=new setgetpostvalue();    		
  $local_account_ids=$setgetpostvalue_obj->localAccoutIDS;
	$account_size=sizeof($local_account_ids);
  $old_value= Array();
  $new_value=Array();
  $field_name=Array();
  $table_name="order";
  
  $action_type1 = $_POST['action_type'];
         
  
	if($action_type1 == "add") 
	{
    $file_name="src/php/manage_add_order.php";
    $order_item_all_1 = $_POST['order_item_all'];
    $order_qty_all_1 = $_POST['order_qty_all'];
    $unit_price_all_1 = $_POST['unit_price_all'];      
    $any_other_cost_all_1 = $_POST['any_other_cost_all'];
    $discount_all_1 = $_POST['discount_all'];
    $total_price_all_1 = $_POST['total_price_all'];
        
    $order_item = explode(',',$order_item_all_1);
    $order_qty = explode(',',$order_qty_all_1);
    $unit_price = explode(',',$unit_price_all_1);
    $any_other_cost = explode(',',$any_other_cost_all_1);
    $discount = explode(',',$discount_all_1);
    $total_price = explode(',',$total_price_all_1);
    
    $size_item = sizeof($order_item); 
    
    $vendor_id = $_POST['vendor_id'];
    $ref_id = $_POST['ref_id'];
    $ref_type_id = $_POST['ref_type_id']; 
  	
    date_default_timezone_set('Asia/Calcutta');    	
    $order_date = date("Y-m-d H:i:s");
    
        
    $action_type="add";  

    //$person_name1=$pd->data[person_name];
    //$person_mob1=$pd->data[person_mob];
    //$other_detail1=$pd->data[other_detail];
    //array('order_id' , 'action_type' , 'order_item', 'order_qty' , 'unit_price', 'any_other_cost', 'discount', 'total_price', 'unit_price', 'vendor_id', 'ref_id', 'ref_type_id');          
    //$local_account_ids=explode(",",$pd->data[local_account_ids]);
    $account_size=sizeof($local_account_ids); 
  	$query ="select Max(sno)+1 as serial_no from order_detail";  ///// for auto increament of geo_id ///////////   
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$max_no= $row->serial_no;
		if($max_no==""){$max_no=1;}
    
    $order_number = "#".$max_no."V".$vendor_id."R".$ref_id; 
		
    $query1 = "INSERT INTO order_detail(order_id,order_number,order_item,order_qty,unit_price,order_date,any_other_cost,discount,total_price,current_status,create_id) VALUES ";    
    
    $query_str1 = "";

    for($i=0;$i<$size_item;$i++)
    {
      //1: order placed, 2:order finalised, 3: order dispatched, 4: Invoice generated 5: cancelled
      if($i==0)
      {
        $query_str1.="('$max_no','$order_number','$order_item[$i]','$order_qty[$i]','$unit_price[$i]','$order_date','$any_other_cost[$i]','$discount[$i]','$total_price[$i]','1','$account_id')";         	  
      }
  	  else
  	  {
        $query_str1.=",('$max_no','$order_number','$order_item[$i]','$order_qty[$i]','$unit_price[$i]','$order_date','$any_other_cost[$i]','$discount[$i]','$total_price[$i]','1','$account_id')";  	                 
      }
    }
      
    $query_str1 = $query1.$query_str1;
    $result1=mysql_query($query_str1,$DbConnection);
    
    $query2 = "INSERT INTO `order`(order_id,vendor_id,ref_id,ref_type_id,status) VALUES('$max_no','$vendor_id','$ref_id','$ref_type_id','1')";
  	$result2=mysql_query($query2,$DbConnection);
    
    echo $query_str1."<br>".$query_str2;
    
    if($result1 && $result2){$flag=1;$action_perform="Added";} 
    
    /*if($result)
	  {
    	$query_string1="INSERT INTO order_grouping(order_id,account_id,status,edit_id,edit_date) VALUES";
  
  		for($i=0;$i<$account_size;$i++)
  		{
  			if($i==$account_size-1)
  			{
  				$query_string2.="('$max_no','$local_account_ids[$i]','1','$account_id','$date');";
  			}
  			else
  			{
  				$query_string2.="('$max_no','$local_account_ids[$i]','1','$account_id','$date'),";
  			}
  		}
  		$query=$query_string1.$query_string2;
      //echo "query=".$query;       		
  		if($DEBUG ==1)print_query($query);     
  		$result=mysql_query($query,$DbConnection);          	  
  		if($result){$flag=1;$action_perform="Added";} 
  	}*/
  }
  else
  {
    $flag=2;
  } 
 
  if($action_type1=="finalise")
	{	       
    $vendor_id = $_POST['vendor_id'];
    $ref_id = $_POST['ref_id'];
    $ref_type_id = $_POST['ref_type_id']; 
  	
    date_default_timezone_set('Asia/Calcutta');    	
    $order_date = date("Y-m-d H:i:s");
            
    $action_type="finalise";    
    
    /*record=1:1:item1:20:500:-:-:10000,
    1:2:item1:20:500:-:-:10000,
    1:3:itemvts1:100:9000:-:-:900000,
    0:4:itemvts2:50:9000:-:-:450000,
    1:5:itemvts3:40:9000:-:-:360000&
    vendor_id=undefined&
    ref_id=undefined&
    ref_type_id=undefined*/

    $record1 = $_POST['record'];
    $record2 = explode(',',$record1);
    for($i=0;$i<sizeof($record2);$i++)
    {
      $record3 = explode(':',$record2[$i]);
      $status[$i] = $record3[0];
      $serial[$i] = $record3[1];
      $order_item[$i] = $record3[2];
      $order_qty[$i] = $record3[3];
      $unit_price[$i] = $record3[4];
      $any_other_cost[$i] = $record3[5];
      $discount[$i] = $record3[6];
      $total_price[$i] = $record3[7];    
    
      $file_name="src/php/manage_edit_delete_order.php"; ///////for previous page
      
    		  		
  		$query="UPDATE order_detail SET order_item='$order_item',order_qty='$order_qty',any_other_cost='$any_other_cost',discount='$discount',total_price='$total_price',unit_price='$unit_price',edit_id='$account_id',edit_date='$date' WHERE order_id='$order_id'";
  		//echo "query=".$query;
  		$result=mysql_query($query,$DbConnection);
  		//$ret_result=track_table($calibration_id,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
  		//if($ret_result=="success" && $result){$flag=1;$action_perform="Updated";}
  		if($result)
      {$flag=1;$action_perform="Updated";} 
      else
      {
        $flag=2;
      }    
	}
	else if ($pd->data[action_type]=="delete")
	{
	  $file_name="src/php/manage_edit_delete_order.php"; ///////for previous page
    $action_type="edit_delete"; 
    $calibration_id=$pd->data[calibration_id];
    $query="SELECT order_id from order_detail where order_id='$order_id' and status=1";
    $result=mysql_query($query,$DbConnection); 
    $numrows = mysql_num_rows($result);
    if($numrows>0)
    {
      $delete_flag=1; 
    }
    else
    {
      $file_name="src/php/manage_edit_delete_order.php"; ///////for previous page   
  		$query="UPDATE order_detail SET edit_id='$account_id',edit_date='$date',status='0' WHERE order_id='$order_id' AND status='1'"; 
  		$result=mysql_query($query,$DbConnection);    
  		$old_value[]="1";$new_value[]="0";$field_name[]="status";     
  		$ret_result=track_table($escalation_id,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
  		if($ret_result=="success" && $result){$flag=1;$action_perform="Deleted";}
  	}
	}

     
	if($flag==1)
	{
		$msg = "Order ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
	else if($flag==2)
	{	  
		$msg = "Order form input is not correct.Plesae enter correct input.";
		$msg_color = "green";				
	}
	else
	{ 	 
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	}
	
	
	if($flag3)
	{
	 $msg = $msg."<br>".$msgtmp."<br>";
  }
  
  if($flag==2)
	{	      
    $common_str="order_id=".$pd->data[order_id]."&action_type=".$action_type."&local_account_ids=".$pd->data[local_account_ids]."&common_id=".$pd->data[local_account_ids]."&person_name=".$pd->data[person_name]."&person_mob=".$pd->data[person_mob]."&other_detail=".$pd->data[other_detail];
	  //echo"common_str=".$common_str;
    echo"<input type='hidden' id='common_id' value='".$common_str."'>";
  }
    
  echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  if($pd->data[action_type]=="add")
  {
    $common_str="action_type=".$pd->data[action_type]; 
    echo"<input type='hidden' id='common_id' value='".$common_str."'>";
    echo'<center><a href="javascript:show_option_with_value(\'manage\',\'add_order\');" class="back_css">&nbsp;<b>Back</b></a></center>';
  }
  else
  {
    $common_str="action_type=".$action_type."&common_id=".$pd->data[local_account_ids]; 
    echo"<input type='hidden' id='common_id' value='".$common_str."'>";
    echo'<center><a href="javascript:manage_action_edit_prev(\''.$file_name.'\');" class="back_css">&nbsp;<b>Back</b></a></center>';
  }
   
?>
        
