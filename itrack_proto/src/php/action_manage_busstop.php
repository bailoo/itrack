<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$DEBUG = 0;
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['local_account_ids'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids);  
  
	$old_value= Array();	$new_value=Array();		$field_name=Array(); 	$table_name="busstop"; 
  
	include_once('track_log.php');
  
	if($action_type1=="add") 
	{     
		$busstop_name1=trim($_POST['busstop_name']);
		$latitude=trim($_POST['latitude']);
		$longitude=trim($_POST['longitude']);
		    
		//$route_coord1=base64_encode(trim($_POST['route_coord']));

		$query2 ="select Max(sno)+1 as seiral_no from busstop";  ///// for auto increament of school_id ///////////   
		$result2=mysql_query($query2,$DbConnection);
		$row1=mysql_fetch_object($result2);
		$max_no= $row1->seiral_no;
		if($max_no==""){$max_no=1;}
		
		$query_string1="INSERT INTO busstop(group_id,busstop_id,busstop_name,longitude,latitude,status,create_id,create_date) VALUES";

		for($i=0;$i<$account_size;$i++)
		{
			//echo "accout_id=".$local_account_ids[$i]."<br>";
			if($i==$account_size-1)
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$busstop_name1','$longitude','$latitude','1','$account_id','$date');";
			}
			else
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$busstop_name1','$longitude','$latitude','1','$account_id','$date'),";
			}
		}
		$query=$query_string1.$query_string2; 
		//echo $query;
		if($DEBUG ==1 )print_query($query);     
		$result=mysql_query($query,$DbConnection);          	  
		if($result){$flag=1;$action_perform="Added";} 
		   
	}
  
	else if($action_type1=="edit")
	{
		$busstop_id1 = $_POST['busstop_id'];    
		$edit_busstop_name1 =trim($_POST['edit_busstop_name']);
		$new_value[]=$edit_busstop_name1;
		
		$edit_busstop_latitude1 =trim($_POST['edit_busstop_latitude']);
		$new_value[]=$edit_busstop_latitude1;
		
		$edit_busstop_longitude1 =trim($_POST['edit_busstop_longitude']);
		$new_value[]=$edit_busstop_longitude1;
	  		    
    
		$query="SELECT * FROM busstop where busstop_id='$busstop_id1' AND status='1'";
		if($DEBUG ==1 )
		print_query($query);
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$busstop_name2=$row->busstop_name;
		$old_value[] =$busstop_name2;
		$field_name[]="busstop_name";
		$longitude2=$row1->longitude;         
		$old_value[] = $longitude2;
		$field_name[]="longitude";
    $latitude2=$row1->latitude;         
		$old_value[] = $latitude2;
		$field_name[]="latitude"; 

		$query="UPDATE busstop SET busstop_name='$edit_busstop_name1',longitude='$edit_busstop_longitude1',latitude='$edit_busstop_latitude1',edit_id='$account_id',edit_date='$date' WHERE busstop_id='$busstop_id1'";
		if($DEBUG ==1 )
		print_query($query);
		$result=mysql_query($query,$DbConnection); 

		$ret_result=track_table($busstop_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		if($ret_result=="success" && $result){$flag=1;$action_perform="Updated";} 
	    
	}
  
	else if ($action_type1=="delete")
	{
		$busstop_id1 = $_POST['busstop_id'];     
		$query="UPDATE busstop SET edit_id='$account_id',edit_date='$date',status='0' WHERE busstop_id='$busstop_id1' AND status='1'"; 
		$result=mysql_query($query,$DbConnection);    
		$old_value[]="1";
		$new_value[]="0";
		$field_name[]="status";     
		$ret_result=track_table($busstop_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
		if($ret_result=="success"  && $result){$flag=1;$action_perform="Deleted";}
	}
	else if($action_type1=="assign")
	{
		/*$local_busstop_ids = $_POST['busstop_ids'];		
		$local_busstop_ids=explode(",",$local_busstop_ids);
		$busstop_size=sizeof($local_busstop_ids);
		*/
    $local_busstop_serial = $_POST['busstop_serials'];
	//echo"local_busstop_serial : ".$local_busstop_serial;
    $local_busstop_serials=explode("#",$local_busstop_serial);
	$serials_size=sizeof($local_busstop_serials);
    $local_busroute_id = $_POST['busroute_id'];
		 $j=0;
		 
		$query="UPDATE busstop_assignment SET edit_id='$account_id',edit_date='$date',status='0' WHERE busroute_id='$local_busroute_id' AND status='1'"; 
		$result=mysql_query($query,$DbConnection);
		 
		$query_string1="INSERT INTO busstop_assignment(busstop_id,busroute_id,busstop_serial,create_id,create_date,status) VALUES";
		for($i=0;$i<$serials_size;$i++)
		{
		  $j=$i;
		  $local_busstop_id=explode(":",$local_busstop_serials[$i]);
		 if($i==$serials_size-1)
			{
				$query_string2.="($local_busstop_id[1],$local_busroute_id,$j,$account_id,'$date',1);";
			}
			else
			{
				$query_string2.="($local_busstop_id[1],$local_busroute_id,$j,$account_id,'$date',1),";
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
		$local_vehicle_ids=explode(",",$local_vehicle_ids);
		$vehicle_size=sizeof($local_vehicle_ids);
		
		$old_value= Array();   $new_value=Array();   $field_name=Array(); $table_name="geo_assignment";

		for($i=0;$i<$vehicle_size;$i++)
		{	
			$query="UPDATE route_assignment SET status=0,edit_id='$account_id',edit_date='$date' WHERE vehicle_id='$local_vehicle_ids[$i]' AND status=1";
			$result=mysql_query($query,$DbConnection); 
			$geo_id1= $veh_geo_id2[1];
			$old_value[]= "1";
			$new_value[]="0";
			$field_name[]= "status";         
			$ret_result=track_table($local_vehicle_ids[$i],$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		}
		
		if($ret_result=="success" && $result){$flag=1;$action_perform="De-Assigned";} 	
	}
	else if($action_type1=="setarrival")
	{
	  $local_account_id = $_POST['account_id'];    
    $local_bus_id = $_POST['bus_id'];
    $local_shift_id = $_POST['shift_id'];
    $local_hrs=$_POST['hrs'];
    $local_hrs=explode("#",$local_hrs);
    $local_mins=$_POST['mins'];
    $local_mins=explode("#",$local_mins);
    $local_busstopids=$_POST['busstopids'];
    //echo "busstopids##".$local_busstopids;
    $local_busstopids=explode("#",$local_busstopids);
    $rowcnt=$_POST['rowcnt'];
    // echo "setarrival##";
    $query_chk="SELECT * from bus_arrival where bus_serial='$local_bus_id' and shift_id='$local_shift_id' AND status='1'"; 
	//echo "query=".$query."<br>";
	$result_chk=mysql_query($query_chk,$DbConnection);
	$row_result=mysql_num_rows($result_chk);		
	if($row_result!=null)
	{
	$query1="UPDATE bus_arrival SET status='0',edit_id='$account_id',edit_date='$date' WHERE bus_serial='$local_bus_id' and shift_id='$local_shift_id'";
	if($DEBUG ==1 )
	print_query($query1);
	$result1=mysql_query($query1,$DbConnection); 		
	}
	
	$query_string1="INSERT INTO bus_arrival(bus_serial,shift_id,busstop_id,arrival_time,create_id,create_date,status) VALUES";
		for($i=0;$i<=$rowcnt;$i++)
		{
		  $j=$i;
		  if($local_hrs[$i]=="-1"){$local_hrs[$i]="00";}
		  if($local_mins[$i]=="-1"){$local_mins[$i]="00";}
		  $local_time=$local_hrs[$i].":".$local_mins[$i].":00";
		  //echo "busstopids##".$local_busstopids[$i];
		 if($i==$rowcnt)
			{
				$query_string2.="($local_bus_id,$local_shift_id,$local_busstopids[$i],'$local_time',$account_id,'$date',1);";
			}
			else
			{
				$query_string2.="($local_bus_id,$local_shift_id,$local_busstopids[$i],'$local_time',$account_id,'$date',1),";
			}
		}
		$query=$query_string1.$query_string2;
		//echo "query=".$query;
	
		if($DEBUG ==1 )print_query($query);     
		$result=mysql_query($query,$DbConnection);          	  
		if($result){$flag=1;$action_perform="Arrivel set";}
		
	}
	
	else if($action_type1=="arrival")
	{
	$local_account_id = $_POST['account_id'];    
	$local_bus_id = $_POST['bus_id'];
	$local_shift_id = $_POST['shift_id'];
	$local_busroute_id="";
	echo "arrival##";
     
          
     //echo $local_account_id .":" ;
     //echo $local_busroute_id;
    $result_string="";
    $i=0;
    $msg="";
    $query="SELECT busroute_id from bus_assignment where bus_serial='$local_bus_id' AND school_id='$local_account_id' AND shift_id='$local_shift_id' AND status='1'"; 
					//echo "query=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$row_result=mysql_num_rows($result);		
					if($row_result!=null)
					{
						while($row=mysql_fetch_object($result))
						{									
							$local_busroute_id=$row->busroute_id;
						}
					}
    
    if($local_busroute_id!="")
    {
      $query="SELECT busstop_assignment.busstop_id,busstop_assignment.busstop_serial,busstop.busstop_name from busstop_assignment,busstop where busstop_assignment.busroute_id='$local_busroute_id' AND busstop_assignment.status='1' AND busstop.status='1' AND busstop_assignment.busstop_id=busstop.busstop_id order by busstop_assignment.busstop_serial";
  					//echo "query=".$query."<br>";
  					$result=mysql_query($query,$DbConnection);
  					$row_result=mysql_num_rows($result);		
  					if($row_result!=null)
  					{
  						while($row=mysql_fetch_object($result))
  						{									
  							$busstop_id=$row->busstop_id;
  							$busstop_serial=$row->busstop_serial;
  							$busstop_name=$row->busstop_name;
  							
                $busstop_ids[$i] = $busstop_id;
                
  							if($i==0){
  							     $msg=$msg.$busstop_serial.":".$busstop_id.":".$busstop_name;
                }
                else{
                    $msg=$msg."#".$busstop_serial.":".$busstop_id.":".$busstop_name;
                }
                $i++;
                
  					   }    
  	        }
  	        
  	        //for Arrival time
  	    $msg=$msg."##";
  	   for($k=0;$k<sizeof($busstop_ids);$k++)
  	   {
  	   
       $query="SELECT distinct busstop_id,arrival_time from bus_arrival where busstop_id='$busstop_ids[$k]' AND bus_serial='$local_bus_id' AND shift_id='$local_shift_id' AND status='1'";
  					//echo "query=".$query."<br>";
  					$result=mysql_query($query,$DbConnection);
  					$row_result=mysql_num_rows($result);		
  					if($row_result!=null)
  					{
  						while($row=mysql_fetch_object($result))
  						{									
  							$busstop_id=$row->busstop_id;
  							$arrival_time=$row->arrival_time;
  							$hrmin=explode(":",$arrival_time);
                
  							if($k==0){
  							     $msg=$msg.$busstop_id.":".$hrmin[0].":".$hrmin[1];
                }
                else{
                    $msg=$msg."#".$busstop_id.":".$hrmin[0].":".$hrmin[1];
                }
                                
  					   }    
  	        }
            else{
                if($k==0){
  							     $msg=$msg.$busstop_ids[$k].":-1:-1";
                }
                else{
                    $msg=$msg."#".$busstop_ids[$k].":-1:-1";
                }
            }     
  	    }    
  	    $flag=2;
	    }
	}

	else if($action_type1=="getbusstops")
	{
	  $local_account_id = $_POST['account_id'];    
    $local_busroute_id = $_POST['busroute_id'];
     echo "getbusstops##";
     
     //echo $local_account_id .":" ;
     //echo $local_busroute_id;
    $result_string="";
    $i=0;
    $msg="";
    $query="SELECT busstop_assignment.busstop_id,busstop_assignment.busstop_serial,busstop.busstop_name from busstop_assignment,busstop where busstop_assignment.busroute_id='$local_busroute_id' AND busstop_assignment.status='1' AND busstop.status='1' AND busstop_assignment.busstop_id=busstop.busstop_id order by busstop_assignment.busstop_serial";
					//echo "query=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$row_result=mysql_num_rows($result);		
					if($row_result!=null)
					{
						while($row=mysql_fetch_object($result))
						{									
							$busstop_id=$row->busstop_id;
							$busstop_serial=$row->busstop_serial;
							$busstop_name=$row->busstop_name;
							
							if($i==0){
							     $msg=$msg.$busstop_serial.":".$busstop_id.":".$busstop_name;
              }
              else{
                  $msg=$msg."#".$busstop_serial.":".$busstop_id.":".$busstop_name;
              }
              $i++;
              
					   }    
	        }
	    $flag=2;
	}
 
	if($flag==1)
	{
		$msg = "Busstop ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
	else if($flag==2)
	{
		echo $msg;			
	}	
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	} 

  if($flag!=2){
 echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  echo'<center><a href="javascript:show_option(\'manage\',\'busstop\');" class="back_css">&nbsp;<b>Back</b></a></center>'; 
   }
  
?>   