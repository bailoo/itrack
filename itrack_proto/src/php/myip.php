<?php
  //if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.181")
    //echo '<br>Time:'.$datetime.' ,lat1='.$lat1.' ,lng1='.$lng1.', lat2='.$lat2.' ,lng2='.$lng2.' ,dist='.$distance.' totaldist='.$total_dist;                         


echo ' Client IP: ';
//if ( isset($_SERVER["REMOTE_ADDR"]) )    {
    echo '<br>remote-add:';
    echo '' . $_SERVER["REMOTE_ADDR"] . ' ';
//}  //if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) )    {
    echo '<br>xforwarded-add:';
    echo '' . $_SERVER["HTTP_X_FORWARDED_FOR"] . ' ';
//}  if ( isset($_SERVER["HTTP_CLIENT_IP"]) )    {
    echo '<br>client_ip:';
    echo '' . $_SERVER["HTTP_CLIENT_IP"] . ' ';
//}

?>