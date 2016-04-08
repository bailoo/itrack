<?php
include_once("mysql.php");
include_once("print_matter.php");
date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
$date=date("Y-m-d H:i:s");	
function my_mysql_query($query,$db)
{
$IP = $_SERVER['REMOTE_ADDR'];
date_default_timezone_set('Asia/Calcutta');
$datetime = date("Y-m-d H:i:s");
$q="INSERT INTO track_query (ip,account_id,query,datetime) VALUES ('$IP','$account_id','$query','$datetime')";
print_query($q);
// $r = mysql_query($q,$db);
$result = mysql_query($q,$db);
return $result;
}  
  
function track_table($id,$table,$field,$old,$new,$account_id,$date,$DbConnection)
{
$DEBUG = 0;
if($DEBUG)
{
  $names=Array(); $datas=Array();
  $i = 1; $names[$i] = "Field"; $datas[$i] = $field;
  $i = 2; $names[$i] = "Old"; $datas[$i] = $old;
  $i = 3; $names[$i] = "New"; $datas[$i] = $new;
  print print_arrays($names, $datas);
}

$msg = "null";
if (sizeof($field)!=sizeof($old) || sizeof($old)!=sizeof($new))
{
  $msg = "mismatch";
}
else
{
  $changeLength=0;
  for ($i=0; $i<sizeof($old); $i++)
  {      
	if (trim($old[$i])!=trim($new[$i]))
	{
	  $changeLength++;
	  $changeField[$changeLength] = $field[$i];
	  $changeOld[$changeLength] = $old[$i];
	  $changeNew[$changeLength] = $new[$i];           
	}
  }
  if($DEBUG)
  {
	$names=Array(); $datas=Array();
	$i = 1; $names[$i] = "Change Field"; $datas[$i] = $changeField;
	$i = 2; $names[$i] = "Change Old"; $datas[$i] = $changeOld;
	$i = 3; $names[$i] = "Change New"; $datas[$i] = $changeNew;
	print print_arrays($names, $datas);
  }
  if ($changeLength > 0)
  {
	$query="INSERT INTO track_log (id,table_name,field_name,old_value,new_value,edit_id,edit_date) VALUES ";
	for ($i=1; $i<$changeLength; $i++)
	{
	  $query.=" ('$id','$table','$changeField[$i]','$changeOld[$i]','$changeNew[$i]','$account_id','$date') ,";
	}
	$query.=" ('$id','$table','$changeField[$i]','$changeOld[$i]','$changeNew[$i]','$account_id','$date')";
	$result=mysql_query($query,$DbConnection);
	if($DEBUG) print_message("Result = ".$result, $query);
	if ($result)
	{
	  $msg = "success";
	}
	else
	{
	  $msg = "fail";
	}
  }
  else
  {
	$msg = "nochange";
  }
}
return $msg; 
} 

?>
