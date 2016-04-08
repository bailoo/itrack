<?php
$stringData = $_POST['csv_string2'];
$csv_type1 = $_POST['csv_type2'];
//echo "csv_string=".$stringData."<br>";
//echo "csv_type1=".$csv_type1."<br>";

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