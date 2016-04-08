<?php
    $download_file = $_POST['filename'];
		$path ="/var/www/html/vts/beta/src/php/download";
		$fullPath = $path."/".$download_file;
      
    if ($fd = fopen ($fullPath,"r")) {
			$fsize = filesize($fullPath);
			$path_parts = pathinfo($fullPath);
			$ext = strtolower($path_parts["extension"]);					
      switch ($ext){
      case "pdf":
      header("Content-type: application/pdf"); // add here more headers for diff. extensions
        header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
				break;
        case "csv":
        header("Content-type: application/csv"); // add here more headers for diff. extensions
        header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
		  	break;
        default; 
				header("Content-type: application/octet-stream");
				header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
			}
			header("Content-length: $fsize");
			header('Pragma: no-cache');
			header('Expires: 0');
	
      $stringData = trim($stringData);
         
      $file = @fopen($fullPath,"r");
      while(!feof($file))
      {
      	//print(@fread($file, 1024*8));
      	echo fread($file, 1024);
      	//echo fread($file, 1024*8);
      	ob_flush();
      	flush();
      }
   }
   
   unlink($fullPath);