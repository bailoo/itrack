<?php
  include_once('src/php/util_session_variable.php');
  include_once("src/php/util_php_mysql_connectivity.php");
  include_once("src/php/util_computer_info.php");	
?>

<?php
  $DEBUG = 0;
  $flag = 0;
  $result_response=1;

	$width = $_POST['width'];
	$height = $_POST['height'];
	$resolution = $_POST['resolution'];
  
	$post_superuser = $_POST['superuser'];	
	$post_user = $_POST['user'];	
	$post_group = $_POST['group'];	
	$post_password = md5($_POST['password']);

  if($DEBUG)
  {  
    echo "Superuser = ".$post_superuser." (Length: ".strlen($post_superuser).") <br>";
    echo "User = ".$post_user." (Length: ".strlen($post_user).") <br>";
    echo "Group = ".$post_group." (Length: ".strlen($post_group).") <br>";
    echo "Password = ".$post_password." (Length: ".strlen($post_password).") <br>";
  }
	
  $query="SELECT account_id FROM account WHERE superuser='$post_superuser' AND user='$post_user' AND grp='$post_group' AND password='$post_password' AND status=1";
  if ($DEBUG) print_query($query);
	$result = mysql_query($query, $DbConnection);
	$count = mysql_num_rows($result);	
	// echo"count=".$count;

  if($count <= 0)
  {
  	$msg = "Not a Registered User! Please Wait ...";
  	$msg_color = "Red";
    $re_url = "index.php";
  	$flag = 0;

  	$_SESSION['id'] = '';
  }
  else
	{
	  $msg = "Registered User! Please Wait ...";
	  $msg_color = "Green";
	  $re_url = "home.php";
	  $flag = 1;
	  
		$row =mysql_fetch_object($result);
		$account_id=$row->account_id;
    $_SESSION['account_id'] = $account_id;
		
    include_once("src/php/util_account_detail.php");
	}
	
	// echo "<br><br><br><br><br><br><center><FONT color=\"".$msg_color."\" size=4><strong><br>".$msg."</strong></font></center>";	
	echo "<FONT color=\"".$msg_color."\" size=4><strong><br>".$msg."</strong></font>";
	
  date_default_timezone_set('Asia/Calcutta');
  $datetime_in = date("Y-m-d H:i:s");
  $_SESSION['datetime_in'] = $datetime_in;

  $query = "INSERT INTO log_login (account_id, superuser, user, grp, password, flag, datetime_in, count, ip, browser_number, browser_working, browser_name, browser, browser_v, os_name, os_number, os, width, height, resolution) VALUES ('$account_id','$post_superuser','$post_user','$post_group','$post_password','$flag','$datetime_in','$count','$ip','$browser_number','$browser_working','$browser_name','$browser','$browser_v','$os_name','$os_number','$os','$width','$height','$resolution')";
  $result1 = mysql_query($query, $DbConnection);
  $result_response = $result_response && $result1;
  if($DEBUG) print_message("Result = ".$result1."/".$result_response, $query);
  
	$query="SELECT log_id FROM log_login WHERE account_id='$account_id' AND datetime_in='$datetime_in'";
	if ($DEBUG) print_query($query);
	$result = mysql_query($query, $DbConnection);
  	
	$row = mysql_fetch_object($result);
	$log_id = $row->log_id;
  $_SESSION['log_id'] = $log_id;
  
	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=".$re_url."\">";

?>
