<?php
	$download_file_name=$_POST['download_file_name'];
	$processType=$_POST['download_process_type'];
	$file_path =$download_file_id_1;
	$download_file_name1=explode(".",$download_file_name);
	//echo "file_path=".$file_path;
	$cmp_flag=0;
	if($processType=="RAW DATA")
	{
		$file_path="demo_group/raw_data/".$download_file_name;	
	}
	else if($processType=="FILTERED DATA")
	{	
		$file_path="demo_group/filtered_data/".$download_file_name;
	}
	else
	{
		$cmp_flag=1;
		rename("demo_group/filtered_data/".$download_file_name,"demo_group/filtered_data/".$download_file_name1[0]."_filtered.txt");
		copy("demo_group/filtered_data/".$download_file_name1[0]."_filtered.txt","demo_group/raw_data/".$download_file_name1[0]."_filtered.txt");
		rename("demo_group/filtered_data/".$download_file_name1[0]."_filtered.txt","demo_group/filtered_data/".$download_file_name);
	}
if($cmp_flag==0)
{
	if(file_exists($file_path))
	{
		if($fd = fopen ($file_path, "r")) 
		{
			$fsize = filesize($file_path);
			$path_parts = pathinfo($file_path);
			//echo "a=".$path_parts["basename"];
			$fnl=explode(".",$path_parts["basename"]);
			$fn2=explode("#",$fnl[0]);
			$path_parts["basename"]=$fn2[0].".".$fnl[1];
			//echo "b=".$path_parts["basename"];
			$ext = strtolower($path_parts["extension"]);	
			switch ($ext) 
			{
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
			  
			while(!feof($fd)) 
			{
				$buffer = fread($fd, 2048);
				echo $buffer;
			}
			fclose ($fd);
			//unlink($file_path); 
		}
	}
	else
	{
		echo 'file not found';
	}
}
else if($cmp_flag==1)
{
	$zip = new ZipArchive();
	$zip_name = "demo_group/raw_data/raw_and_filtered.zip"; // Zip name
	$zip->open($zip_name,  ZipArchive::CREATE);
	$files[]=$download_file_name;
	$files[]=$download_file_name1[0]."_filtered.txt";
	
	foreach ($files as $file) {
  echo $path = "demo_group/raw_data/".$file;
  if(file_exists($path)){
  $zip->addFromString(basename($path),  file_get_contents($path));  
  }
  else{
   echo"file does not exist";
  }
}
$zip->close();

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=".basename($zip_name).";" );
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($zip_name));
readfile("$zip_name");
unlink("demo_group/raw_data/".$download_file_name1[0]."_filtered.txt");
unlink($zip_name);
}
?>