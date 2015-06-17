<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$DEBUG=0;
	set_time_limit(18000);
	
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['local_account_ids'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids);
	$type = $_POST['file_type'];
	//echo "type=".$type." ,account_size=".$account_size." ,local_account_ids=".$local_account_ids." ,action_type1=".$action_type1;

	//TYPE =0 : CUSTOMER
	//TYPE =1 : PLANT
	//echo  "<br>LOCAL AC=".$local_account_ids;

	$old_value= Array();		$new_value=Array();			$field_name=Array();		$table_name="station"; 

  function check_availability($customer,$name)
  {
   global $type;
    //echo "<br>CHECK";
    global $DbConnection;
    global $account_id;
 //   $query = "SELECT customer_no FROM station WHERE customer_no='$customer' AND station_name='$name' AND type='$type' AND status=1";
    $query = "SELECT customer_no FROM station WHERE customer_no='$customer' AND type='$type' AND user_account_id='$account_id' AND status=1";
    //echo "<br>Q=".$query;
    $result = mysql_query($query,$DbConnection);
    $numrows = mysql_num_rows($result);
//	echo "numrows=".$numrows;
    return $numrows;   
  } 
  
	if($action_type1=="add") 
	{ 
		//echo "Uploaded successfully";

    $target_path = "upload/";
    
    $target_path = $target_path . basename( $_FILES['file']['name']); 
    
    //move_uploaded_file($_FILES['file']['tmp_name'], $target_path);
    
    if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) 
    {
        //echo "The file ".  basename( $_FILES['file']['name']). " has been uploaded";
    } else
    {
        //echo "There was an error uploading the file, please try again!";
    }
    
    //***************READ XLS FILE RECORD ***********************
    /////////////////////////////////////////////////////////////     

    //$path = "/var/www/html/vts/beta/src/php/upload/".$_FILES['file']['name'];
    $path = "/var/www/html/vts/beta/src/php/upload/".$_FILES['file']['name'];
        
    $row = 1;
    if (($handle = fopen($path, "r")) !== FALSE) {
    
        $record ="";
        $Latitude ="";
        $Longitude = "";
        $LandMark ="";    //CUSTOMER_NO IN CASE OF PLANT
        $Point ="";       //STATION NAME IN CASE OF PLANT
        $Address ="";
        $CompanyName ="";
          
        $i=0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            //echo "<p> $num fields in line $row: <br /></p>\n";
           $radius = ""; 
	   $row++;
            if($i>0)
            {
              for ($c=0; $c < $num; $c++) {
                  //echo $data[$c] . "<br />\n";
                  $record = $data[$c];
                  //echo "<br>Rec=".$record;
                  
                  if($c==0)
                    $Latitude = $record;
                  
                  else if($c==1)
                  $Longitude = $record;
                  
                  else if($c==2)
                  {
                    $LandMark = $record;   //CUSTOMER NO IF TYPE=1=PLANT
                  //echo "<br>LM=".$LandMark;
                  }
                  
                  else if($c==3)
                    $Point = $record;     //STATION NAME IF TYPE=1=PLANT
                  

		  else if($c==4)
		    $radius = $record;

                  if($type==0)
                  {
                    if($c==5)
                      $Address = $record;
                  
                    else if($c==6)
                      $CompanyName = $record;
                  }                                 
              }
            }
            


                for($k=0;$k<sizeof($prev_customer);$k++)
                {
                        //echo "<br>prev_cust=".$prev_customer[$k]." ,landmark=".$LandMark;

                        if($prev_customer[$k] == $LandMark)
                        {
                                //echo "<br>CustomerMatched";
                                $LandMark = "";
                                break;
                        }
                }

                $prev_customer[] = $LandMark;


          	if($LandMark!="")
          	{
              $cstatus = check_availability($LandMark,$Point);
              //echo "<br>st=".$cstatus;
              if($cstatus ==0)
              {
                $customer_no[] = $LandMark;
              	$station_name[] = $Point;
                $station_coord[] = $Latitude.", ".$Longitude;    	
              	$station_radius[] = $radius;
		if($type==0)
              	{
                  $remarks[] = $Address.":".$CompanyName;
                }
            	}
          	}   
                     
            $i++;
        }
        fclose($handle);
    }
     
    /*
    require_once 'Excel/reader.php';
    // ExcelFile($filename, $encoding);
    $data = new Spreadsheet_Excel_Reader();
    // Set output Encoding.
    $data->setOutputEncoding('CP1251');  
    $path = "/var/www/html/vts/beta/src/php/upload/".$_FILES['file']['name'];
    //echo "<br>".$path;
          
    $data->read($path);
        
    error_reporting(E_ALL ^ E_NOTICE);
    
    for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {

      $record ="";
      $Latitude ="";
      $Longitude = "";
      $LandMark ="";
      $Point ="";
      $Address ="";
      $CompanyName ="";
      
      for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
    		//echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
    		$record = $data->sheets[0]['cells'][$i][$j];
    		
        //echo "<br>REC:".$record;
        
        if($j==1)
          $Latitude = $record;
        
        else if($j==2)
        $Longitude = $record;
        
        else if($j==3)
        $LandMark = $record;
        
        else if($j==4)
        $Point = $record;
        
        else if($j==5)
        $Address = $record;
        
        else if($j==6)
        $CompanyName = $record; 
               
    		//echo str_replace('"','',$data);
    		//echo "<br>";
    	}
    	
    	if($record!="" && $LandMark!="")
    	{
        $customer_no[] = $LandMark;
      	$station_name[] = $Point;
        $station_coord[] = $Latitude.", ".$Longitude;    	
      	$remarks[] = $Address.":".$CompanyName;
    	}
    	//echo "\n";
    	//echo "<br><br>";
    
    }   */       
   
   unlink($path); 
    
    //***********************************************************

      //echo "C=".sizeof($customer_no)."<br><br>";      
      for($x=0;$x<sizeof($customer_no);$x++)
      {
    		//echo "<br>C=".$customer_no[$x];
        	$query ="select Max(sno)+1 as serial_no from station";  ///// for auto increament of geo_id ///////////   
    		$result=mysql_query($query,$DbConnection);
    		$row=mysql_fetch_object($result);
    		$max_no= $row->serial_no;
    		$query = "";
    		$query_string1 ="";
    		$query_string2 ="";
    		
        	if($max_no==""){$max_no=1;}
    
    		$query_string1="INSERT INTO station(user_account_id,station_id,customer_no,station_name,station_coord,distance_variable,type,status,create_id,create_date,remark) VALUES";
    
    		//echo "<br>ASize=".$account_size;
        	for($i=0;$i<$account_size;$i++)
    		{
			/*$dist_var = 0.1;
			if( ($customer_no[$x] >= 1) && ($customer_no[$x] <= 19999))
			{
				$dist_var = 0.2;
			}
                        else if( ($customer_no[$x] >= 1000000) && ($customer_no[$x] <= 1999999))
                        {
                                $dist_var = 0.2;
                        }
                        else if( ($customer_no[$x] >= 70000) && ($customer_no[$x] <= 75999))
                        {
                                $dist_var = 0.5;
                        }*/
			if($station_radius[$x] == "")
			{
				$dist_var = '0.1';	
			}
			else
			{
				$dist_var = $station_radius[$x];
			}	

    			//echo "accout_id=".$local_account_ids[$i]."<br>";
    			if($i==$account_size-1)
    			{
    				$query_string2.="('$local_account_ids[$i]','$max_no','$customer_no[$x]','$station_name[$x]','$station_coord[$x]','$dist_var','$type','1','$account_id','$date','$remarks[$x]');";
    			}
    			else
    			{
    				$query_string2.="('$local_account_ids[$i]','$max_no','$customer_no[$x]','$station_name[$x]','$station_coord[$x]','$dist_var','$type','1','$account_id','$date','$remarks[$x]'),";
    			}
    		}
    		$query=$query_string1.$query_string2; 
    		//echo "<br>".$query;  		
  		
    		if($DEBUG ==1 )print_query($query);     
    		$result=mysql_query($query,$DbConnection);          	  
    		if($result){$flag=1;$action_perform="Added";}
      }  //OUTER FOR CLOSED               
	}
  
	if($action_type1=="edit")
	{
		//$type="edit_delete";
		$geo_id1 = $_POST['station_id'];    
		$geo_name1 =trim($_POST['station_name']);
		$new_value[]=$geo_name1;
		/* $query="SELECT geo_name FROM geofence WHERE create_id='$account_id' AND geo_name='$geo_name1' AND user_account_id='$local_account_ids' AND status='1'";
		if($DEBUG ==1 )print_query($query);
		$result=mysql_query($query,$DbConnection);
		$num_rows=mysql_num_rows($result);      
		if($num_rows1>0)
		{$flag=1;}
		else
		{  */    
		$geo_coord1 =base64_encode(trim($_POST['station_coord']));     
		$new_value[]=$geo_coord1;           

		$query="SELECT * FROM station where station_id='$geo_id1' AND status='1'";
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$geo_name2=$row->geo_name;
		$old_value[] =$geo_name2;
		$field_name[]="station_name";
		$geo_coord2=$row->geo_coord;         
		$old_value[] = $geo_coord2;
		$field_name[]="station_coord"; 

		$query="UPDATE station SET station_name='$geo_name1',station_coord='$geo_coord1',edit_id='$account_id',edit_date='$date' WHERE station_id='$geo_id1'";
		//echo "query=".$query;
		$result=mysql_query($query,$DbConnection); 

		$ret_result=track_table($geo_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		if($ret_result=="success" && $result){$flag=1;$action_perform="Updated";} 
		//}     
	}
	else if ($action_type1=="delete")
	{
		// $type="edit_delete";
		$station_id1 = $_POST['station_id'];    
		$query1 = "SELECT station_id FROM station_assignment WHERE station_id='$station_id1' AND status=1";
		$result1=mysql_query($query1,$DbConnection);  
		$numrows = mysql_num_rows($result1);
		
    if($numrows)
    {
      $action_perform="Sorry! This station is assigned to some vehicle! First deassign, then delete"; 
      $flag=3;
    }
    else
    {
      $query="UPDATE station SET edit_id='$account_id',edit_date='$date',status='0' WHERE station_id='$station_id1' AND status='1'"; 
  		$result=mysql_query($query,$DbConnection);    
  		$old_value[]="1";$new_value[]="0";$field_name[]="status";     
  		$ret_result=track_table($station_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
  		$flag=2;$action_perform="Deleted";
		}
	}
	else if($action_type1=="assign")
	{
		$local_station_ids = $_POST['station_ids'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_station_ids=explode(",",$local_station_ids);
		$station_size=sizeof($local_station_ids);
		//echo "vehicle_size=".$vehicle_size."<br>";
		$local_vehicle_id = $_POST['vehicle_id'];
		
		$query_string1="INSERT INTO station_assignment(station_id,vehicle_id,create_id,create_date,status) VALUES";

		for($i=0;$i<$station_size;$i++)
		{
			//echo "local_geofenc_id=".$local_geofenc_id[$i]."<br>";
			if($i==$station_size-1)
			{
				$query_string2.="($local_station_ids[$i],$local_vehicle_id,$account_id,'$date',1);";
			}
			else
			{
				$query_string2.="($local_station_ids[$i],$local_vehicle_id,$account_id,'$date',1),";
			}
		}
		$query=$query_string1.$query_string2; 
		//echo "query=".$query;
	
		if($DEBUG ==1 )print_query($query);     
		$result=mysql_query($query,$DbConnection);          	  
		if($result){$flag=1;$action_perform="Assigned";} 		
	}
	else if($action_type1=="deassign")
	{
		$local_vehicle_ids = $_POST['vehicle_ids'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_vehicle_ids=explode(",",$local_vehicle_ids);
		$vehicle_size=sizeof($local_vehicle_ids);	
		
		$old_value= Array();   $new_value=Array();   $field_name=Array(); $table_name="station_assignment";

    for($i=0;$i<$vehicle_size;$i++)
		{	
      $local_all_ids=explode(":",$local_vehicle_ids[$i]);
      $vehicle_id = $local_all_ids[0];
      $station_id = $local_all_ids[1];
        			
      $query="UPDATE station_assignment SET status=0,edit_id='$account_id',edit_date='$date' WHERE vehicle_id='$vehicle_id' AND station_id='$station_id' AND status=1";
			//echo $query;
      $result=mysql_query($query,$DbConnection); 
			//$station_id1= $veh_geo_id2[1];
			$old_value[]= "1";
			$new_value[]="0";
			$field_name[]= "status";         
			$ret_result=track_table($local_vehicle_ids[$i],$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		}
		
		if($ret_result=="success" && $result){$flag=1;$action_perform="De-Assigned";} 	
	}
 
	if($flag==1)
	{
		$msg = "Station ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
	else if($flag==3)
	{
		$msg = $action_perform;
		$msg_color = "red";		
	}
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	}
  
  echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  echo'<center><a href="#" onclick="javascript:window.close();" class="back_css">&nbsp;<b>Close this window</b></a></center>';                 
  
?>
        
