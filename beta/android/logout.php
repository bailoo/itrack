<?php	
  include_once("utilSetUnsetSession.php");
  //echo "ac=".$account;
  unset($_SESSION['accountIdSession']);
	
	print"<br><br><br><br><br><br><br><br><br><center><FONT color=\"red\" font size=\"2\" face=\"verdana\"><strong>Session logout .. </strong></font></center>";
  echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=index.php\">";
?>


</body>
</html>
