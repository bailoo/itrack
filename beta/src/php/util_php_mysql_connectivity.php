<?php
	$file = "setup/mysql.php"; if(file_exists($file)) { include_once($file); }
	$file = "../../setup/mysql.php"; if(file_exists($file)) { include_once($file); }

	$HOST = "localhost";
	// $DBASE = "iespl_vts_beta";
	// $USER = "root";
	// $PASSWD = "mysql";
	//$HOST = "db.itracksolution.co.in";
	//$HOST = "111.118.181.156";
	//$HOST = "111.118.182.147";
	$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
	mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
  
  //date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
	$date=date("Y-m-d H:i:s");

$query = "select * from vehicle limit 10";
$res = mysql_query($query,$DbConnection);
$numrows = mysql_num_rows($res);
//echo "\nNumrows=".$numrows;
	
 // set_time_limit();

	function print_query($query)
	{
    echo '<fieldset style="font-family:courier;color:blue;text-align:left"><legend style="color:red">Query</legend>'.$query.'</fieldset>';
  }
  	
	function print_message($name, $message)
	{
    echo '<fieldset style="font-family:courier;color:gray;text-align:left"><legend style="color:black">'.$name.'</legend>'.$message.'</fieldset>';
  }
  
  function my_mysql_query($query,$db)
  {
    $IP = $_SERVER['REMOTE_ADDR'];
    //date_default_timezone_set('Asia/Calcutta');
  	$datetime = date("Y-m-d H:i:s");
    $q="INSERT INTO track_query (ip,account_id,query,datetime) VALUES ('$IP','$account_id','$query','$datetime')";
    print_query($q);
    // $r = mysql_query($q,$db);
    $result = mysql_query($q,$db);
    return $result;
  }
  
  function print_array($name,$data)
  {
    $msg="<center>";
    $msg.="Size of <i><b>".$name."</b></i> Array = ".sizeof($data)."<br>";
    $msg.="<table border=2 cellpadding=4>";
    $msg.="<tr><th>#</th><th>Index</th><th>Value</th></tr>";
    $i=1;
    foreach ($data as $key=>$value)
    {
      $msg.="<tr><td>".($i++)."</td><td>".$key."</td><td>".$value."</td></tr>";
    }
    $msg.="</table>";
    $msg.="</center>";
    return $msg;
  }
  
  function print_arrays($names,$datas)
  {
    $maxLength=0;
    foreach ($datas as $index=>$value)
    {
      $maxLength=max($maxLength,sizeof($value));
    }
    
    $header="Number of Arrays = ".sizeof($names)."<br>";
    $header.="Maximum length in Arrays = ".$maxLength."<br>";
    
    $table="<table border=2 cellpadding=4>";
    $table.="<tr><th>".sizeof($names)."\\".$maxLength."</th>";
    for ($i=1; $i<=$maxLength; $i++)
    {
      $table.="<th>".$i."</th>";
    }
    $table.="</tr>";
    
    foreach ($names as $key=>$tname)
    {
      $data = $datas[$key];
      // print print_array($tname, $data);
      
      $table.="<tr>";
      $table.="<th>".$key."=><i><b>".$tname."</b></i> (".sizeof($data).")</th>";
      foreach ($data as $index=>$value)
      {
        $table.="<td>".$index."=>".$value."</td>";
      }
      $table.="</tr>";
    }
    $table.="</table>";
    
    $msg="<center>".$header.$table."</center>";
    return $msg;
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
