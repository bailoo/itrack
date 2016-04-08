<?php	
  include_once("util_session_variable.php");
  //echo "ac=".$account;
  unset($_SESSION['account']);
	
	print"<br><br><br><br><br><br><br><br><br><center><FONT color=\"red\" font size=\"2\" face=\"verdana\"><strong>Session logout .. </strong></font></center>";
  echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=index.php\">";
?>


</body>
</html>
