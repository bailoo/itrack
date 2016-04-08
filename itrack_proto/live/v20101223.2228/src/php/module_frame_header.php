<?php
  # Using HTTP_HOST      
  //$domain = $_SERVER['HTTP_HOST'];
  //echo "domain1=".$domain."<br>";      
  # Using SCRIPT_NAME      
  $path = $_SERVER['SCRIPT_NAME'];
  //echo "full path=".$path."<br>";
  $url_substr = explode('/',$path);
  $size = sizeof($url_substr);
  // echo "<br>size=".$size;
  $interface = $url_substr[$size-1];
  //echo "interface name=".$interface."<br>";
?>

<table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%" class='frame_header_table'>  <!-- TABLE TOP OPENS-->
  <tr>
    <td>
      <?php
        echo "&nbsp;Welcome ".$superuser.",".$user.",".$grp." (".$account_type." / ".$permission_type.")";
      ?>
    </td>
    <td align="right">
      <?php
        if($interface == "home.php")    {echo '<b class="hs1">Home</b>';} else { echo '<a href="home.php" class="hs2">Home</a>';}
        echo ' | ';
        if($interface == "live.php")  {echo '<b class="hs1">Live</b>';} else { echo '<a href="live.php" class="hs2">Live</a>';}
        echo ' | ';
        if($interface == "manage.php")  {echo '<b class="hs1">Manage</b>';} else { echo '<a href="manage.php" class="hs2">Manage</a>';}
        echo ' | ';
        if($interface == "report.php")  {echo '<b class="hs1">Report</b>';} else { echo '<a href="report.php" class="hs2">Report</a>';}
        echo ' | ';
        if($interface == "setting.php") {echo '<b class="hs1">Setting</b>';} else { echo '<a href="setting.php" class="hs2">Setting</a>';}
        echo ' | ';
        if($interface == "help.php") {echo '<b class="hs1">Help</b>';} else { echo '<a href="help.php" class="hs2">Help</a>';}
        echo ' | ';
        if($interface == "feedback.php") {echo '<b class="hs1">Feedback</b>';} else { echo '<a href="feedback.php" class="hs2">Feedback</a>';}
        echo ' | <a href="logout.php" class="hs2">Logout</a>&nbsp;'; 
      ?>         
    </td>
  </tr>
</table>   <!-- TABLE CLOSE OPENS-->