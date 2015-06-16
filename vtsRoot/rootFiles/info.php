<?php
  include_once('src/php/util_session_variable.php');
  include_once("src/php/util_php_mysql_connectivity.php");
  // include_once("src/php/util_browser_detection.php");
  include_once("src/php/util_computer_info.php");
?>

<html>
  <head>
    <?php
      include('src/php/main_frame_part1.php')
    ?> 
  </head>

<body>
  <?php
    echo "<table align=center border=2 cellpadding=2 cellspacing=2 >";
    echo "<tr><th>Session</th><th>Contents</th></tr>";
    foreach ($_SESSION as $VarName => $Value)  {
      echo "<tr><td>".$VarName."</td><td>".$Value."</td></tr>";
    }
    echo "</table>";
  ?>
  <hr>
  <?php
    echo "<table align=center border=2 cellpadding=2 cellspacing=2 >";
    echo "<tr><th>Item</th><th>Value</th></tr>";
    echo "<tr><td>browser_number</td><td>".$browser_number."</td></tr>";
    echo "<tr><td>browser_working</td><td>".$browser_working."</td></tr>";
    echo "<tr><td>browser_name</td><td>".$browser_name."</td></tr>";
    echo "<tr><td>os_name</td><td>".$os_name."</td></tr>";
    echo "<tr><td>os_number</td><td>".$os_number."</td></tr>";
    echo "<tr><td>OS</td><td>".$os."</td></tr>";
    echo "<tr><td>Browser</td><td>".$browser."</td></tr>";
    echo "<tr><td>Version</td><td>".$browser_v."</td></tr>";
    echo "<tr><td>IP</td><td>".$ip."</td></tr>";
    echo "</table>";
  ?>
  </body>
  <?php
    mysql_close($DbConnection);
  ?>
</html>