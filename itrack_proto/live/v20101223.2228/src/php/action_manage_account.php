<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');

  $DEBUG=0;	
	
  $post_action_type = $_POST['action_type'];
  
  if($post_action_type =="add")
  {
    $flag=0;
  	$result_response=1;
  
    // Get all POST data	
    $post_login=$_POST['login'];
    $post_password=md5($_POST['password']);
    $post_ac_type=$_POST['ac_type'];
    $post_company_type=$_POST['company_type'];
    $post_perm_type=$_POST['perm_type'];
    $post_admin_perm=$_POST['admin_perm'];
    $list_fname="";
    $list_fvalue="";
    for ($fi=1; $fi<=$feature_count; $fi++)
    {
      $post_fvalue[$fi]=$_POST[$fname[$fi]];
      $list_fname.=",".$fname[$fi]; // Used in query for insertion
      $list_fvalue.=",'".$post_fvalue[$fi]."'"; // Used in query for insertion
    }
  
    if($DEBUG)
    {  
      echo "Login = ".$post_login." <br>";
      echo "Password = ".$post_password." <br>";
      echo "A/C Type = ".$post_ac_type." <br>";
      echo "Company Type = ".$post_company_type." <br>";
      echo "Permission Type = ".$post_perm_type." <br>";
      echo "Admin Permission = ".$post_admin_perm." <br>";
      for ($fi=1; $fi<=$feature_count; $fi++) { echo ucfirst(strtolower($fname[$fi]))." = ".$post_fvalue[$fi]. "<br>"; }
    }
  
    // superuser,user,grp for new account  
    $superuser1 = $superuser;
    $user1 = $user;
    $grp1 = $grp;
    if ($account_type=="Root")
    {
      $superuser1 = $post_login;
    }
    else if ($account_type=="Superuser")
    {
      $user1 = $post_login;
    }
    else if (($permission_type=="Distributor") && ($post_ac_type=="0")) // New user account made by dirtributor
    {
      $user1 = $post_login;
      $grp1 = "admin";
    }
    else
    {
      $grp1 = $post_login;
    }
    
    // Check if the account already exist
  	$query="SELECT account_id FROM account WHERE superuser='$superuser1' AND user='$user1' AND grp='$grp1'";
    if($DEBUG) print_query($query);
  	$result=mysql_query($query,$DbConnection);
  	$count = mysql_num_rows($result);
    if($count > 0)
    {
    	$flag = -1;
    }
    else
  	{
      // permission of new account
      if ($account_type=="Root")
      {
        $permission1 = 1; // New account is Superuser
      }
      else if (($account_type=="Superuser") || ($permission_type=="Distributor"))
      {
        $permission1 = $post_ac_type + 1; // New account is either User or Distributor
      }
      else
      {
        $permission1 = $post_ac_type; // New account is grp
      }
      
      // Vehicle Group ID for new account
      if($post_perm_type=="select")
      {
      	$query="SELECT vehicle_group_id FROM account_detail WHERE account_id='$post_admin_perm'";
    	}
    	else
    	{
      	$query="SELECT MAX(vehicle_group_id)+1 as vehicle_group_id FROM account_detail";
      }
      if($DEBUG) print_query($query);
    	$result=mysql_query($query,$DbConnection);
    	$row=mysql_fetch_object($result);
    	$vehicle_group_id1=$row->vehicle_group_id;
      
      // Admin ID for new account
      if($post_perm_type=="select")
      {
      	$query="SELECT admin_id FROM account_detail WHERE account_id='$post_admin_perm'";
    	}
    	else
    	{
      	$query="SELECT MAX(admin_id)+1 as admin_id FROM account_detail";
        $post_admin_perm=-1;  	
      }
      if($DEBUG) print_query($query);
    	$result=mysql_query($query,$DbConnection);
    	$row=mysql_fetch_object($result);
    	$admin_id1=$row->admin_id;
    
      // Admin of new account
      if(($post_admin_perm==$account_id) && ($grp!="admin")) // condition of self and not a user
      {
        $query="SELECT account_admin_id as admin_id FROM account_detail WHERE account_id='$account_id'";
      }
      else // condition of new or non-self
      {
        $query="SELECT admin_id FROM account_detail WHERE account_id='$account_id'";
      }
      if($DEBUG) print_query($query);
      $result=mysql_query($query,$DbConnection);
    	$row=mysql_fetch_object($result);
    	$admin_id2=$row->admin_id;
    	
    	// Get company ID of new account
      $query="SELECT company_id FROM account_detail WHERE account_id='$account_id'";
      if($DEBUG) print_query($query);
    	$result=mysql_query($query,$DbConnection);
    	$row=mysql_fetch_object($result);
    	$company_id=$row->company_id;
    	if($post_company_type=="new")
    	{
        $query="SELECT logo_id,company_name,copyright_name FROM company_info WHERE company_id='$company_id'";
        if($DEBUG) print_query($query);
        $result=mysql_query($query,$DbConnection);
        $row=mysql_fetch_object($result);
        $logo_id=$row->logo_id;
        $company_name1=$row->company_name;
        $copyright_name1=$row->copyright_name;
  
        $query="SELECT logo_file FROM company_logo WHERE logo_id='$logo_id'";
        if($DEBUG) print_query($query);
        $result=mysql_query($query,$DbConnection);
        $row=mysql_fetch_object($result);
        $logo_file1=$row->logo_file;
      }
    	
    	// Preference Setting
      $query="SELECT time_zone FROM account_preference WHERE account_id='$account_id'";
      if($DEBUG) print_query($query);
      $result=mysql_query($query,$DbConnection);
    	$row=mysql_fetch_object($result);
      $time_zone1=$row->time_zone;
      $latlng1 = "1"; // Default Value
      $refresh_rate1 = "1"; // Default Value
    
      // Date of account creation
      date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
    	$date=date("Y-m-d H:i:s");
    
      // Insertion into account table
      $query="INSERT INTO account (superuser,user,grp,password,status,create_id,create_date) VALUES ('$superuser1','$user1','$grp1','$post_password','1','$account_id','$date')";
      $result1=mysql_query($query,$DbConnection);
      $result_response = $result_response && $result1;
      if($DEBUG) print_message("Result = ".$result1."/".$result_response, $query);  
    
      // Get new account_id  	
    	$query="SELECT account_id FROM account WHERE superuser='$superuser1' AND user='$user1' AND grp='$grp1'";
      if($DEBUG) print_query($query);
    	$result=mysql_query($query,$DbConnection);
    	$row=mysql_fetch_object($result);
    	$account_id1=$row->account_id;
      // echo "account_id1 = ".$account_id1;
  
      // creating new company id
    	if($post_company_type=="new")
    	{
        $query="INSERT INTO company_logo (logo_file,status,create_id,create_date) VALUES ('$logo_file1','1','$account_id','$date')";
        $result1=mysql_query($query,$DbConnection);
        $result_response = $result_response && $result1;
        if($DEBUG) print_message("Result = ".$result1."/".$result_response, $query);
        
        $query="SELECT logo_id FROM company_logo WHERE logo_file='$logo_file1' AND create_date='$date'";
        if($DEBUG) print_query($query);
      	$result=mysql_query($query,$DbConnection);
      	$row=mysql_fetch_object($result);
      	$logo_id1=$row->logo_id;
      	
      	$query="INSERT INTO company_info (logo_id,company_name,copyright_name,status,create_id,create_date) VALUES ('$logo_id1','$company_name1','$copyright_name1','1','$account_id','$date')";
        $result1=mysql_query($query,$DbConnection);
        $result_response = $result_response && $result1;
        if($DEBUG) print_message("Result = ".$result1."/".$result_response, $query);
        
        $query="SELECT company_id FROM company_info WHERE logo_id='$logo_id1' AND create_date='$date'";
        if($DEBUG) print_query($query);
      	$result=mysql_query($query,$DbConnection);
      	$row=mysql_fetch_object($result);
      	$company_id1=$row->company_id;
      }
      else
      {
        $company_id1=$company_id;
      }
      
      // adding account_detail of new account
      $query="INSERT INTO account_detail (account_id,vehicle_group_id,company_id,admin_id,account_admin_id,permission,create_id,create_date) VALUES ('$account_id1','$vehicle_group_id1','$company_id1','$admin_id1','$admin_id2','$permission1','$account_id','$date')";
      $result1=mysql_query($query,$DbConnection);
      $result_response = $result_response && $result1;
      if($DEBUG) print_message("Result = ".$result1."/".$result_response, $query);
    
      // Setting of account feature
      $query="INSERT INTO account_feature (account_id".$list_fname.",create_id,create_date) VALUES ('$account_id1'$list_fvalue,'$account_id','$date')";
      $result1=mysql_query($query,$DbConnection);
      $result_response = $result_response && $result1;
      if($DEBUG) print_message("Result = ".$result1."/".$result_response, $query);
    
      // Setting of account preference
      $query="INSERT INTO account_preference (account_id,time_zone,latlng,refresh_rate,create_id,create_date) VALUES ('$account_id1','$time_zone1','$latlng1','$refresh_rate1','$account_id','$date')";
      $result1=mysql_query($query,$DbConnection);
      $result_response = $result_response && $result1;
      if($DEBUG) print_message("Result = ".$result1."/".$result_response, $query);
      
    	if($result_response)
    	{
    	 $flag=1;
      }
    }
  	
    if ($flag==-1)
    {
    	echo "<br><font color=\"Red\" size=4><strong>Account Already Exist! Please try again ...</strong></font>";
    }
    if ($flag==1)
  	{
      echo "<br><font color=\"Green\" size=4><strong>Account Created Successfully!</strong></font>";
  	}					
  	else if($flag==0)
  	{
      echo "<br><font color=\"Red\" size=4><strong>Unable to create Account due to some server problem!</strong></font>";
  	}
  	else
  	{
      echo "<br><font color=\"Blue\" size=4><strong>Sorry! Unable to process request!</strong></font>";
    }
    // echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=add_account.php\">";
  } // IF POST_ACTION TYPE CLOSED
  
  else if($post_action_type =="edit")
  {
     echo "test ok";
  }

?>
        