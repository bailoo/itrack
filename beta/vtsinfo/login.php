<?php
include_once("util_session_variable.php");

$account = $_POST['password'];
//echo "account_login:".$account;

if($account=="info14iespl")
{
  $_SESSION['account'] = $account;
  
  print"<br><br><br><br><br><br><br><br><br><center><FONT color=\"green\" font size=\"2\" face=\"verdana\"><strong>Redirecting ... </strong></font></center>";
  echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=home.php\">";   
}
else
{
 print"<br><br><br><br><br><br><br><br><br><center><FONT color=\"red\" font size=\"2\" face=\"verdana\"><strong>Invalid password... </strong></font></center>";
 echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=index.php\">";  
}

?>
