<? ob_start(); header('Cache-Control: no-store, no-cache, must-revalidate');

	$data = $_REQUEST['mapdata'];
	
	mysql_connect('localhost','root','mysql');
	mysql_select_db('mapdir');

	if($_REQUEST['command']=='fetch')
	{
		$query = "select polypoint_value from polypoint where sno=1";
		if(!($res = mysql_query($query)))die(mysql_error());		
		$rs = mysql_fetch_array($res,1);
		die($rs['polypoint_value']);		
	}
?>