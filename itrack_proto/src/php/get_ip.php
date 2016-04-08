<?php

//echo "Remote addr: " . $_SERVER['REMOTE_ADDR']."<br/>";

//echo "X Forward: " . $_SERVER['HTTP_X_FORWARDED_FOR']."<br/>";

//echo "Clien IP: " . $_SERVER['HTTP_CLIENT_IP']."<br/>";

//echo $_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.189";

if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.189")
{
  echo "<br>IP Macthed:".$_SERVER['HTTP_X_FORWARDED_FOR'];
}
else
{
  echo "<br>IP Did not Macth:".$_SERVER['HTTP_X_FORWARDED_FOR'];
}

?>