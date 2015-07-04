<?php
$download_file_id_1=$_POST['download_file_id'];
$file_path =$download_file_id_1;
echo "file_path=".$file_path;

if(file_exists($file_path))
{
	if($fd = fopen ($file_path, "r")) 
	{
		$fsize = filesize($file_path);
		$path_parts = pathinfo($file_path);
		$fnl=explode(".",$path_parts["basename"]);
		$fn2=explode("#",$fnl[0]);
		$path_parts["basename"]=$fn2[0].".".$fnl[1];
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
		unlink($file_path); 
	}
}
else
{
	echo 'file not found';
}
?>