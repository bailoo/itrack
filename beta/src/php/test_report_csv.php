<?php
//$stringData = $_POST['csv_string'];
//$csv_type1 = $_POST['csv_type'];

$stringData = "";
for($i=0;$i<500;$i++)
{
  $sno = $i+1;
  $data1 = "data1".$sno;
  $data2 = "data2_sdfdsfsdfsdfsdfsdfsdfsdf".$sno;
  $data3 = "data3_dsfsdfsdfsdfsdfsdfsdfdfsdfsdfsdfsdfsdfsdfsdfsdfsdfsdfdfsdf".$sno;
  $data4 = "data4_dfasdasdsfdsfsdfsdfsdfsdfsdfsdfsdfsdfdsdfdsfsdfsdfsdfsdfdfsd fdsdfsvdfsvsdvsdsdfsdf".$sno;
  $stringtmp = $sno.",".$data1.",".$data2.$data3.$data4."\n";
  
  if($i==0)
  {
    $stringData = $stringData.$stringtmp;
  }
  else
  {
    $stringData = $stringData.",".$stringtmp;
  }
}


$csv_type1 = "test";

//CREATE FILE
$t=time();
$filename = "report_".$csv_type1."_".$t.".csv";
$root_dir = getcwd();
$path = $root_dir."/csv_reports/".$filename;

//echo "<br>path1=".$path;

$fh = fopen($path, 'w') or die("can't open file");
fwrite($fh, $stringData);
fclose($fh);
// CREATE FILE CLOSED

/// DOWNLOAD SCRIPT
if(file_exists(trim($path)))      
{	
  //$path = $_SERVER['DOCUMENT_ROOT']."/".$filepath; // change the path to fit your websites document structure   
  if ($fd = fopen ($path, "r")) 
  {
      $fsize = filesize($path);
      $path_parts = pathinfo($path);
      $ext = strtolower($path_parts["extension"]);
      switch ($ext) {
          case "pdf":
          header("Content-type: application/pdf"); // add here more headers for diff. extensions
          header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
          break;
          default;
          header("Content-type: application/octet-stream");
          header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
      }
      header("Content-length: $fsize");
      header("Cache-control: private"); //use this to open files directly
          
      while(!feof($fd)) {
          $buffer = fread($fd, 2048);
          echo $buffer;
      }
  }
  fclose ($fd);
  //exit;
    
  /*echo "<br><br><font color=green>Downloding file...</font>";
  if($buffer)
    echo "<br><br><br><font color=green><strong>file Download Successful...</strong></font>"; */
    
  //UNLINK FILE AFTER DOWNLOAD
  
  //$del_path = "csv_reports/".$filename;
  //echo "<br>path2=".$path;
  unlink($path); 
  
  exit;   
}
/// SCRIPT CLOSED

?>				