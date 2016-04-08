<html>
<head>
  <title>GCM Demo</title>
</head>
<body>
<?
	$DBASE = "gcm";
	$USER = "root";
	$PASSWD = "mysql";
	$HOST = "localhost";
	$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("could not connect to DB test");
	mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
  
  $reg_id=$_POST['reg_id'];
 //echo "REG".$reg_id;

 	$query="INSERT INTO deviceRegistration (registrationID) VALUES('$reg_id')";
	//echo "Query=".$query."<br>";				
	$result1=mysql_query($query,$DbConnection);
	if($result1)
	{
	  echo "Registration ID Added Successfully!";							
	}
	else
	{				    
		echo "Unable to process request";
	}		


?>
  
</body>
</html>