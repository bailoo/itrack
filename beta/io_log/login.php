<?php
include_once("util_session_variable.php");

$account = $_POST['password'];
//echo "account_login:".$account;

if($account=="2012io")
{
  $_SESSION['account'] = $account;
  
  print"<FONT color=\"green\" font size=\"2\" face=\"verdana\"><strong>Please wait ... </strong></font>";
  echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=home.php\">";   
}
else
{
 print"<FONT color=\"red\" font size=\"2\" face=\"verdana\"><strong>Invalid password... </strong></font>";
 echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=index.php\">";  
}

?>