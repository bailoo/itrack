<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');

  // Get Superuser, user, and grp of the loggedi-in id
  $query="SELECT superuser,user,grp FROM account WHERE account_id='$account_id'";
  // print_query($query);
  $result=mysql_query($query,$DbConnection);
  $row=mysql_fetch_object($result);
  $superuser=$row->superuser;
  $user=$row->user;
  $grp=$row->grp;
  $_SESSION['superuser'] = $superuser;
  $_SESSION['user'] = $user;
  $_SESSION['grp'] = $grp;
  // echo "Superuser = ".$superuser." ; User = ".$user." ; Group = ".$grp;

  // Get admin_id and permission of logged-in id
  $query="SELECT admin_id,permission,vehicle_group_id FROM account_detail WHERE account_id='$account_id'";
  // print_query($query);
  $result=mysql_query($query,$DbConnection);
  $row=mysql_fetch_object($result);
  $admin_id=$row->admin_id;
  $permission=$row->permission;
  $vehicle_group_id=$row->vehicle_group_id;  
  $_SESSION['admin_id'] = $admin_id;
  $_SESSION['permission'] = $permission;
  $_SESSION['vehicle_group_id'] = $vehicle_group_id;
  // echo "Admin_ID = ".$admin_id." ; Permission = ".$permission;
  
  // get permission type
  switch ($permission)
  {
    case 0: $permission_type="View"; break;
    case 1: $permission_type="Edit"; break;
    case 2: $permission_type="Distributor"; break;
    default: $permission_type="Unknown";
  }
  $_SESSION['permission_type'] = $permission_type;

  // get account type
  if ($superuser=="admin")
    $account_type="Root";
  else if ($user=="admin")
    $account_type="Superuser";
  else if ($grp=="admin")
    $account_type="User";
  else
    $account_type="Group";
  $_SESSION['account_type'] = $account_type;
  
  // Get feature names and values in $fname_i and $fvalue_i where i is the feature index.
  $query="SELECT column_name FROM information_schema.columns WHERE table_name='account_feature' AND ordinal_position>'7'";
  $result=mysql_query($query,$DbConnection);
	$feature_count=mysql_num_rows($result);
	$fi=0;
	$list_fname="";
	while ($row=mysql_fetch_object($result))
	{
    $fi++;
    $fname[$fi]=$row->column_name;
    if ($fi<$feature_count) $list_fname.=$fname[$fi].",";
  }
  $list_fname.=$fname[$fi];
  $query="SELECT ".$list_fname." FROM account_feature WHERE account_id='$account_id'";
  // print_query($query);
  $result=mysql_query($query,$DbConnection);
  $row=mysql_fetch_object($result);
  for ($fi=1; $fi<=$feature_count; $fi++)
  {
    $fvalue[$fi]=$row->$fname[$fi];
  }
  $_SESSION['feature_count'] = $feature_count;
  $_SESSION['fname'] = $fname;
  $_SESSION['fvalue'] = $fvalue;
  $_SESSION['list_fname'] = $list_fname;
?>             
