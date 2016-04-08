<?php

function get_master_detail($account_id)
{
	global $vehicle_t;
	global $transporter;	

	$dir = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master";
	$dh = opendir($dir);
	while (($file = readdir($dh)) !== false) {
		//echo "<A HREF=\"$file\">$file</A><BR>\n";
		$file_tmp = explode("#",$file);
		$file_ext = explode(".",$file_tmp[2]);

 		if($file_ext[0] == "6")	//######## TRANSPORTER FILE
		{
			$path = $dir."/".$file;

			$row = 1;
			if (($handle = fopen($path, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				//echo "<p> $num fields in line $row: <br /></p>\n";
				$row++;
				for ($c=0; $c < $num; $c++) {
				 //echo "\ndata=".$data[$c] . "<br />\n";
					if($c==0)
					{
						$vehicle_t[] = $data[$c];
					}
					else if($c==1)
					{
						$transporter[] = $data[$c];
					}                                 
				}
			}
				fclose($handle);
		}     
		} //IF FORMAT 6
	}   
	closedir($dh);

} //function closed
   
?>
