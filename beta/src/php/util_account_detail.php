<?php  	
	$DEBUG=0;
	$query="SELECT user_id,group_id,user_type FROM account WHERE account_id='$account_id'";
	if($DEBUG){print $query;}
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);  
	$user=@$row->user;
	$user=@$row->group_id;
	$user_type=@$row->user_type;
	$_SESSION['session_user_substation']  = $user_type;
	
	$query="SELECT user_type_id FROM account_feature WHERE account_id='$account_id'";
	if($DEBUG){print $query;}
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);
	$user_type_id=$row->user_type_id;
	
	$user_typeid_array = explode(",",$user_type_id); 
	$_SESSION['user_typeid_array'] = @$user_typeid_array;	
	$size_utype_session = sizeof($user_typeid_array);
        	
	$_SESSION['user_id'] = $user_id;
	if(@$group_id!="")
	{
		$_SESSION['group_id'] = @$group_id;
	}
	

	$query="SELECT admin_id,permission,vehicle_group_id FROM account_detail WHERE account_id='$account_id'";
	if($DEBUG){print $query;}
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);
	$admin_id=$row->admin_id;
	$user_permission=$row->permission;
	$_SESSION['session_user_permission']  = $user_permission;
	$vehicle_group_id=$row->vehicle_group_id;  
	$_SESSION['admin_id'] = $admin_id; 
	$_SESSION['vehicle_group_id'] = $vehicle_group_id;	

	// get permission type
	switch (@$permission)
	{
		case 0: $permission_type="View"; break;
		case 1: $permission_type="Edit"; break;
		case 2: $permission_type="Distributor"; break;
		default: $permission_type="Unknown";
	}
	
	$_SESSION['permission_type'] = $permission_type;

	// Get feature names and values in $fname_i and $fvalue_i where i is the feature index.
	//$query="SELECT column_name FROM information_schema.columns WHERE table_name='account_feature' AND ordinal_position>'7'";
	
  
  //////// GET FIELD NAME ASSIGNED FROM ACCOUNT_FEATURE  /////////////////
  $query="SELECT DISTINCT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='account_feature' AND TABLE_SCHEMA='".$DBASE."' AND ORDINAL_POSITION>'8'";
  //echo $query;
  $result=mysql_query($query,$DbConnection);
	$feature_count=mysql_num_rows($result);
	$fi=0;
	$list_fname="";
	while ($row=mysql_fetch_object($result))
	{
    $fi++;
    $fname[$fi]=$row->COLUMN_NAME;                             // FIELD NAME
    if ($fi<$feature_count) $list_fname.=$fname[$fi].",";
  }
  $list_fname.=$fname[$fi];
  $query="SELECT ".$list_fname." FROM account_feature WHERE account_id='$account_id'";
  //print_query($query);
  $result=mysql_query($query,$DbConnection);
  $row = mysql_fetch_object($result); 
      
  //echo "fcount=".$feature_count."<br>";  
  $k=0;
  for ($fi=1; $fi<=$feature_count; $fi++)
  {      
      //echo "<br>row=".$row->$fname[$fi]." ,fi=".$fi;      
      $fvalue[]=$row->$fname[$fi];           // FIELD VALUE
      //echo "<br>fname=".$fname[$fi]." ,fv=".$fvalue[$fi-1];       
      if($fvalue[$fi-1]==1)
      {
        $fname_assigned = $fname[$fi];     // ASSIGNED FIELD NAME         
        $query = "SELECT feature_id, feature_name FROM feature_mapping WHERE field_name='$fname_assigned' AND status=1";
        if($DEBUG){print $query;}
        $result2= mysql_query($query,$DbConnection);
        $row2 = mysql_fetch_object($result2);
          
        $feature_id_session[$k] = $row2->feature_id;       
        $feature_name_session[$k]  = $row2->feature_name;
		$feature_name_session_login[$k]  = $row2->feature_name; 		
		if($row2->feature_name=="station")
		{
			$flag_station_login=1;
		}
		if($row2->feature_name=="visit_track")
		{
			$visit_track_login=1;
		}
        //echo "<br>feature_id_session=".$feature_id_session[$k];
        //echo "<br>feature_name_session=".$feature_name_session[$k];
        if($feature_name_session[$k]=="live_default")
        {
          $live_default = 1;
        }
        $k++;         
      }
  }    
  ///////////////////////////////////////////////////////////////  
  
  // GET FEATURE_ID FROM USER_TYPE_ID
	for($i=0;$i<$size_utype_session;$i++)
	{
		//echo "size=".$user_typeid_array[$i]."<br>";
	 $query = "SELECT user_type_name FROM user_type WHERE user_type_id=$user_typeid_array[$i]";
	 $result = mysql_query($query, $DbConnection);
	 $row = mysql_fetch_object($result);
	 $user_type_id_session[$i] = $user_typeid_array[$i];
	 $user_type_name_session[$i] = $row->user_type_name;
	 
	 //echo "<br>utypeid=".$user_type_id_session[$i];
	// echo "<br>utypename=".$user_type_name_session[$i];
	 
	 /*$query = "SELECT feature_id FROM usertype_mapping WHERE user_type_id=$user_typeid_session[$i] AND status=1";
	 $result = mysql_query($query,$DbConnection);
	 $row = mysql_fetch_row($result);
	 $feature_id  = $row[0];
	 $feature_id_array = explode(",",$feature_id);     // ALL FIELD NAMES
	 $size_feature[$i] = sizeof($feature_id_array);    // TOTAL SIZE OF FEATURES IN USER TYPE
	 for($j=0;$j<$size_feature[$i];$j++)
	 {	    
      $query = "SELECT feature_name FROM feature_mapping WHERE feature_id=$feature_id_array[$j] AND status=1";
      $result = mysql_query($query,$DbConnection);
      $row = mysql_fetch_row($result); 
      $feature_id_session[] = $feature_id_array[$j];
      $feature_name_session[]  = $row[0];     
	 } */
  }
  
  $_SESSION['size_utype_session'] = $size_utype_session;     // SINGLE
  $size_feature = sizeof($feature_id_session);
  if($DEBUG){echo "<br>size=".$feature_id_session;}
  $_SESSION['size_feature_session'] = $size_feature;    // ARRAY TYPE
  
  $_SESSION['user_type_id_session'] = $user_type_id_session;        // ARRAY TYPE
  $_SESSION['user_type_name_session'] = $user_type_name_session;    // ARRAY TYPE
  
//  echo "feature_id=".$feature_id_session."<br>";
  
  $_SESSION['feature_id_session'] = $feature_id_session;             // ARRAY TYPE
  $_SESSION['feature_name_session'] = $feature_name_session;         // ARRAY TYPE
  
  $query_live = "SELECT color_code FROM color_setting WHERE user_account_id='$account_id' AND feature_name='live' AND status=1";
  $result_live = mysql_query($query_live,$DbConnection);
  $color_code = "";
  if($row_live = mysql_fetch_object($result_live))
  {
	$color_code = $row_live->color_code;
  }
  $_SESSION['live_color'] = $color_code; 		 
  /*  
  $query = "SELECT field_name FROM feature_mapping AND status=1";  
  //$result = mysql_query($query,$DbConnection);  
  $result=mysql_query($query,$DbConnection);
	$feature_count=mysql_num_rows($result);
	$fi=0;
	$list_fname="";
	while ($row=mysql_fetch_object($result))
	{
		$fi++;
		//$fname[$fi]=$row->column_name;   
		$fname[$fi]=$row->field_name;   
		if ($fi<$feature_count) $list_fname.=$fname[$fi].",";
	}
	$list_fname.=$fname[$fi];   */
	
	$_SESSION['feature_count'] = $feature_count;   //NUMBER OF FIELDS NAMES 
	$_SESSION['fname'] = $fname;                   // FIELD NAME ARRAY
	//$_SESSION['fvalue'] = $fvalue;
	$_SESSION['$list_fname_session'] = $list_fname;         // ALL FEILD NAME WITH COMMA SEPARATED
		
?>             
