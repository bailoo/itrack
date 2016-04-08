<? ob_start(); header('Cache-Control: no-store, no-cache, must-revalidate');

	$data = $_REQUEST['mapdata'];
	
	mysql_connect('localhost','root','mysql');
	mysql_select_db('mapdir');
	
	if($_REQUEST['command']=='save')
	{
		
		$query = "update mapdir set value='$data'";
		if(mysql_query($query))die('bien');
		die(mysql_error());
	}
	
	if($_REQUEST['command']=='fetch')
	{
		$query = "select value from mapdir";
		if(!($res = mysql_query($query)))die(mysql_error());		
		$rs = mysql_fetch_array($res,1);
		die($rs['value']);		
	}
?>