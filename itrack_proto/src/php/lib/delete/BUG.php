<?php

class BUG
{
	public static function debug($msg)
	{
		print "DEBUG: " . $msg . "<br>\n";
	}

	public static function debugNoNL($msg)
	{
		print $msg;
	}

	public static function debugArray($msg, $arr)
	{
		print "DEBUG: " . $msg . " : ";
		print_r($arr);
		print "<br>\n";
	}

	public static function printQuery($query)
	{
    echo '<fieldset style="font-family:courier;color:blue;text-align:left"><legend style="color:red">Query</legend>'.$query.'</fieldset>';
  }
  	
	public static function printMessage($name, $message)
	{
    echo '<fieldset style="font-family:courier;color:gray;text-align:left"><legend style="color:black">'.$name.'</legend>'.$message.'</fieldset>';
  }

  public static function printArray($name,$data)
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
    // return $msg;
		echo $msg;
  }
  
  public static function printArrays($arrays)
  {
		$maxLength=0;
		$names = array_keys($arrays);
		for($i=0; $i<sizeof($names); $i++)
		{
			self::printArray($names[$i], $arrays[$names[$i]]);
		}
  }
  
  public static function printArrays1($names,$datas)
  {
    $maxLength=0;
    foreach ($datas as $index=>$value)
    {
      $maxLength=max($maxLength,sizeof($value));
    }

		$names = array_keys($datas);
    
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
    // return $msg;
		echo $msg;
  }
  


}

?>
