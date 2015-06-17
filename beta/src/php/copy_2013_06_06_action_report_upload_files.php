<html>
	<head>
		<style>
		.file_upload_fieldset
		{
		  font-size:11px;
		  font-family:Arial;
	      color:black;
		  font-weight:bold;
		}
		table.menu
		{
			font-size: 10pt;
			margin: 0px;
			padding: 0px;
			font-weight: normal;
		}
		</style>
	</head>
	<body>
<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('station_sort.php');

//error_reporting(E_ALL ^ E_NOTICE);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
//date_default_timezone_set('Europe/London');
require_once 'PHPExcel/IOFactory.php';

set_time_limit(200);
$date1=explode(" ",$date);
if($account_id!="")
{
	$destfile="gps_report/".$account_id;
	if(!file_exists($destfile))
	{
		mkdir($destfile);		
	}
	if(!file_exists("gps_report/".$account_id."/download"))
	{
		mkdir("gps_report/".$account_id."/download");
	}
	if(!file_exists("gps_report/".$account_id."/master"))
	{
		mkdir("gps_report/".$account_id."/master");
	}
	if(!file_exists("gps_report/".$account_id."/upload"))
	{
		mkdir("gps_report/".$account_id."/upload");
	}
	echo"<table width='100%' height='10%'>
			<tr>
				<td>
				</td>
			</tr>
		</table>";
		if($upload_type=="master")
		{	
			$format_ids_1=explode(":",$format_ids);		
			$j=0;	
			$store_files=opendir("gps_report/".$account_id."/master");
			while($file_name = readdir($store_files)) 
			{
				if($file_name!="." && $file_name!="..")
				{
					$file_name_1=explode("#",$file_name);		
					$post_formatid = substr($file_name_1[2], 0,1); // returns "d"
					//echo "format_id=".$post_formatid."<br>";
					$post_formatid_arr[]="#".$post_formatid;
					$post_filename_arr[]=$file_name;
				}
			}		
			$file_ids_arr=explode(",",$format_ids_1[1]);	
		}
		else if($upload_type=="get_report")
		{
			$random_number=rand();
			$format_ids_1=explode(":",$format_ids);	
			if(!file_exists("gps_report/".$account_id."/upload/".$date1[0]))
			{
				mkdir("gps_report/".$account_id."/upload/".$date1[0]);
			}		
			$j=0;
			$file_ids_arr=explode(",",$format_ids_1[1]);
		}
		//print_r($file_ids_arr);
		global $flag;
		$flag=0;
		for($i=0;$i<sizeof($file_ids_arr);$i++)
		{		
			$j++;							
			$file_name=str_replace(" ","_",trim($_FILES['file_'.$format_ids_1[0]."_".$file_ids_arr[$i]]['name']));	
			$file_name_1=explode(".",$file_name);
			$cnt=count($file_name_1);
			if($cnt>1)
			{
				$file_name_str = "";		
				for($k=0;$k<sizeof($file_name_1);$k++)
				{
					if($k==sizeof($file_name_1)-1)
					{
						$extension = $file_name_1[$k];
					}
					else
					{				
						$file_name_str = $file_name_str.$file_name_1[$k].".";
					}
				}
				$file_name_str=substr($file_name_str,0,-1);
				
				if($upload_type=="master")
				{
					$final_file_name=$file_name_str.$file_ids_arr[$i].$format_ids_1[0].".".$extension;
				}
				else if($upload_type=="get_report")
				{
					$final_file_name=$file_name_str."#".$random_number.$file_ids_arr[$i].$format_ids_1[0].".".$extension;
				}
			}
			else
			{									
				if($upload_type=="master")
				{
					$final_file_name=$file_name_1[0].$file_ids_arr[$i].$format_ids_1[0].".".$file_name_1[1];
				}
				else if($upload_type=="get_report")
				{
					$final_file_name=$file_name_1[0]."#".$random_number.$file_ids_arr[$i].$format_ids_1[0].".".$file_name_1[1];
				}
			}
					
			$tmp_upload_file="tmp_upload_file/".$final_file_name;
			if($upload_type=="master")
			{
				$csv_flag=1;
				if($account_id=='231' || $account_id=="723")
				{
					$get_file_extension=explode(".",$final_file_name);
					if($get_file_extension[1]=="xls" || $get_file_extension[1]=="xlsx")
					{
						$csv_flag=0;
						echo'<table border="0" align="center">
								<tr>
									<td>
										<fieldset class="file_upload_fieldset">
											<legend>File Upload Message</legend>';
											echo"<table width='100%' height='5%'>
													<tr>
														<td>
														</td>
													</tr>
													</table>
													<table align='center' class='menu'>
														<tr>
															<td>
																<b>
																	<font color='red'>
																		Sorry Failed to copy
																	</font>
																	(Only csv format is allowed).
																</b>.
															</td>
														</tr>
													</table>
										</fieldset>
									</td>
								</tr>
							</table>"; 
						break;
					}					
				}
			
				if($csv_flag==1)
				{
					$final_dest_file="gps_report/".$account_id."/master/".$final_file_name;					
					if($format_ids_1[0]=="#3")
					{						
						delete_files($post_formatid_arr,$post_filename_arr,$format_ids_1[0]);
						echo'<table border="0" align="center">
								<tr>
									<td>
										<fieldset class="file_upload_fieldset">
											<legend>File Upload Message</legend>';
											upload_files($final_dest_file,$format_ids_1[0],$upload_type,$file_ids_arr[$i]);
									echo'</fieldset>
									</td>
								</tr>
							</table>';
						$flag=1;
					}
					else
					{	
						$file_extension=explode('.',$final_file_name);
						//print_r($file_extension);
						if($file_extension[1]=="xls" || $file_extension[1]=="xlsx")
						{										
							validate_excel_fields($format_ids_1[0],$upload_type,$final_file_name,$file_ids_arr[$i],$tmp_upload_file);
						//echo "flag_status=".$flag."<br>";
						}
						else if($file_extension[1]=="csv")
						{	
							//echo "in else if";
							validate_csv_fields($format_ids_1[0],$upload_type,$final_file_name,$file_ids_arr[$i],$tmp_upload_file);
						}					
						unlink($tmp_upload_file);
						//delete_files($post_formatid_arr,$post_filename_arr,$format_ids_1[0]);
						//////// for delete all files simultaniously ////////
						$delete_post_formatid_arr[]=$post_formatid_arr;
						$delete_post_filename_arr[]=$post_filename_arr;
						$delete_format_ids_1[]=$format_ids_1[0];
					
						
						//////// for upload all files simultaniously ////////
						$upload_final_dest_file[]=$final_dest_file;
						$upload_format_ids_1[]= $format_ids_1[0];
						$upload_upload_type[]=$upload_type;
						$upload_file_ids_arr[]=$file_ids_arr[$i];
						//upload_files($final_dest_file,$format_ids_1[0],$upload_type,$file_ids_arr[$i]);				
					}
				}
			}
			else if($upload_type=="get_report")
			{			
				$final_dest_file="gps_report/".$account_id."/upload/".$date1[0]."/".$final_file_name;																	
				validate_excel_fields($format_ids_1[0],$upload_type,$final_file_name,$file_ids_arr[$i],$tmp_upload_file);
				unlink($tmp_upload_file);	
					//////// for upload all files simultaniously ////////										
				$upload_final_dest_file[]=$final_dest_file;
				$upload_format_ids_1[]=$format_ids_1[0];
				$upload_upload_type[]=$upload_type;
				$upload_file_ids_arr[]=$file_ids_arr[$i];
			}										
		}								

}
else
{
	echo'<table border="0" align="center">
			<tr>
				<td>
					<fieldset class="file_upload_fieldset">
						<legend>File Upload Message</legend>';
						echo"<table width='100%' height='5%'>
								<tr>
									<td>
									</td>
								</tr>
								</table>
								<table align='center'>
									<tr>
										<td>
											Session Logout Please Login Again
										</td>
									</tr>
								</table>
					</fieldset>
				</td>
			</tr>
		</table>";
		$re_url="logout.php";
		echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=".$re_url."\">";
}

	function delete_file_with_error_message($f_code,$original_file_name,$tmp_upload_file,$tmp_val)
	{
		//echo "in delete <br>";
	unlink($tmp_upload_file);	
	echo'<table border="0" align="center">
			<tr>
				<td>
					<fieldset class="file_upload_fieldset">
						<legend>File Upload Message</legend>
							<table border="0" class="menu">
								<tr>
									<td>
										<font color="red">
											<b>'.$original_file_name.' is not uploaded due to incorrect format in cell </b>
										</font>
										<font color="black">
											<b>'.$tmp_val.'</b>					
										</font>
										<font color="red">
											<b>Please correct it.</b>
										</font>			
									</td>
								</tr>
							</table>
					</fieldset>
				</td>
			</tr>
		</table>';			
	}

	if($flag==0)
	{
		for($i=0;$i<sizeof($delete_post_formatid_arr);$i++)
		{		
			delete_files($delete_post_formatid_arr[$i],$delete_post_filename_arr[$i],$delete_format_ids_1[$i]);	
		}
		if($csv_flag==1)
		{
			//echo "in if 1<br>";
		echo'<table border="0" align="center">
				<tr>
					<td>
						<fieldset class="file_upload_fieldset">
							<legend>File Upload Message</legend>';
							for($i=0;$i<sizeof($upload_final_dest_file);$i++)
							{
								upload_files($upload_final_dest_file[$i],$upload_format_ids_1[$i],$upload_upload_type[$i],$upload_file_ids_arr[$i]);
							}
					echo'</fieldset>
					</td>
				</tr>
			</table>';
		}	
	}		
	function upload_files($final_dest_file,$format_ids_cnd,$upload_type,$sub_files_ids)
	{
		$account_id=$_SESSION['account_id'];
		//echo "final_dest_file=".$final_dest_file." format_ids_cnd=".$format_ids_cnd."sub_files_ids=".$sub_files_ids." upload_type=".$upload_type."account_id=".$_SESSION['account_id']."<br>";
		if(isset($final_dest_file))
		{					
			if(copy($_FILES['file_'.$format_ids_cnd."_".$sub_files_ids]['tmp_name'],$final_dest_file))
			{
			echo'<table border="0" align="center" class="menu">
					<tr>
						<td>
							<font color="green">
								<b>File Uploaded Successfully.</b>
							</font>
						</td>
					</tr>
				</table>';
				if(($upload_type=="master" && $format_ids_cnd=="#4") && ($account_id=='231' || $account_id=='723' || $account_id=='2'))
				{
					$shift_time = "ZPME";
					get_master_detail($account_id, $shift_time); 
				}
				if(($upload_type=="master" && $format_ids_cnd=="#5") && ($account_id=='231' || $account_id=='723' || $account_id=='2'))
				{
					//echo "in if <br>";
					$shift_time = "ZPMM";
					get_master_detail($account_id, $shift_time);
				}
			}
			else
			{
			echo'<table border="0" align="center" class="menu">
				<tr>
					<td>
						<font color="green">
							<b>Failed to Copy.</b>
						</font>
					</td>
				</tr>
			</table>';
			}
		}		
	}			
	function delete_files($post_formatid_arr,$post_filename_arr,$format_ids_cnd)
	{
		$account_id=$_SESSION['account_id'];
		if(isset($post_formatid_arr))
		{
			for($fd=0;$fd<sizeof($post_formatid_arr);$fd++) //////delete previous master file with same id coming from post method
			{
				//echo " format_id_prev=".$post_formatid_arr[$fd]." coming_format_id=".$format_ids_cnd." filename=".$post_filename_arr[$fd]."<br>";
				if($format_ids_cnd==$post_formatid_arr[$fd])
				{		
					unlink("gps_report/".$account_id."/master/".$post_filename_arr[$fd]);
				}
			}
		}
	}
	
	function validate_excel_fields($format_ids_cnd,$upload_type,$final_file_name,$sub_files_ids,$tmp_upload_file)
	{	
		global $flag;																
		copy($_FILES['file_'.$format_ids_cnd."_".$sub_files_ids]['tmp_name'],$tmp_upload_file);								
		$original_file_name=basename($_FILES['file_'.$format_ids_cnd."_".$sub_files_ids]['name']);	
		$objPHPExcel = PHPExcel_IOFactory::load($tmp_upload_file);
		$highestColumm = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
		$highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
		//echo "highestRow=".$highestRow." highestColumm=".$highestColumm."<br>";
		$k=0;								
		foreach ($objPHPExcel->setActiveSheetIndex(0)->getRowIterator() as $row) 
		{
			$k++;					
			if($k>1)
			{
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);						
				$ci=0;						
				foreach ($cellIterator as $cell) 
				{
					$ci++;							
					if (!is_null($cell)) 
					{							
						$column = $cell->getColumn();
						$row = $cell->getRow();
						$tmp_val=$column.$row;	
						$f_code=$objPHPExcel->getActiveSheet()->getStyle($tmp_val)->getNumberFormat()->getFormatCode();	
						$cell_value=$cell->getCalculatedValue();
						//echo "format_id=".$format_ids_cnd."<br>";
						if((($upload_type=="master") && ($format_ids_cnd=="#4" || $format_ids_cnd="#5")) || ($upload_type=="get_report" && $format_ids_cnd=="#1"))
						{
							//echo "in if 1111<br>";
							if($ci==1 || $ci==3)
							{								
								if((is_numeric($cell_value)!=1) || ($f_code!="mm-dd-yy" && $f_code!="dd-mm-yy" && $f_code!="mm/dd/yy" && $f_code!="dd/mm/yy"))
								{										
									delete_file_with_error_message("",$original_file_name,$tmp_upload_file,$tmp_val);										
									$flag=1;														
									break;												
								}												
							}
							if($ci==2 || $ci==4)
							{
								//echo "in 24<br>";
								if($f_code!="h:mm:ss" || (is_float($cell_value)!=1))
								{										
									delete_file_with_error_message("h:mm:ss",$original_file_name,$tmp_upload_file,$tmp_val);	
									$flag=1;														
									break;													
								}
							}
							if($ci==8)
							{
								$vehicle_name_local=$objPHPExcel->getActiveSheet()->getCell($tmp_val)->getValue();
								//echo "temp_value=".$tmp_val."<br>";
								//echo "temp_value=".$vehicle_name_local."<br>";	
								/*if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $vehicle_name_local))*/
								if (preg_match('/[\'^£$%&}{@#~?><>,|=+¬]/', $vehicle_name_local))
								{
									delete_file_with_error_message("h:mm:ss",$original_file_name,$tmp_upload_file,$tmp_val);	
									$flag=1;														
									break;						
								}									
							}
						}
						else if(($upload_type=="master") && ($format_ids_cnd=="#6"))
						{
							if($ci==2)
							{
								$column = $cell->getColumn();
								$row = $cell->getRow();
								$tmp_val=$column.$row;
								$vehicle_name_local=$objPHPExcel->getActiveSheet()->getCell($tmp_val)->getValue();
								/*echo "temp_value=".$tmp_val."<br>";
								echo "temp_value=".$vehicle_name_local."<br>";*/	
								/*if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $vehicle_name_local))*/
								if (preg_match('/[\'^£$%&}{@#~?><>,|=+¬]/', $vehicle_name_local))
								{
									delete_file_with_error_message("h:mm:ss",$original_file_name,$tmp_upload_file,$tmp_val);	
									$flag=1;														
									break;						
								}									
							}
						}
						else if($upload_type=="master")
						{
							if($ci==3 || $ci==4)
							{
								$column = $cell->getColumn();
								$row = $cell->getRow();
								$tmp_val=$column.$row;
								$f_code=$objPHPExcel->getActiveSheet()->getStyle($tmp_val)->getNumberFormat()->getFormatCode();				
								$cell_value=$cell->getCalculatedValue();
								if($f_code!="h:mm" || (is_float($cell_value)!=1))
								{									
									delete_file_with_error_message("h:mm",$original_file_name,$tmp_upload_file,$tmp_val);								
									$flag=1;														
									break;
								}									
							}
						}
						else if($upload_type=="get_report" && $format_ids_cnd=="#2")
						{
							//echo "file_ids_arr=".$sub_files_ids."<br>";
							if($sub_files_ids=="#1")
							{
								// echo"in if<br>";
								if($ci==1)
								{
									$cell_value="";
									$cell_value=$cell->getCalculatedValue();
									strlen($cell_value);
									//echo "cell_value1=".$cell_value[2]." cell_value=".$cell_value[5]."<br>";
									if($cell_value[2]!="." || $cell_value[5]!=".")
									{													
										delete_file_with_error_message("dd.mm.yy",$original_file_name,$tmp_upload_file,$tmp_val);										
										$flag=1;															
										break;
									}
								}
								if($ci==5 || $ci==6)
								{
									$cell_value=$cell->getCalculatedValue();
									if($f_code!="h:mm:ss" || (is_float($cell_value)!=1))
									{
										delete_file_with_error_message("h:mm:ss",$original_file_name,$tmp_upload_file,$tmp_val);	
										$flag=1;															
										break;
													
									}
								}
							}
							if($sub_files_ids=="#2")
							{
								if($ci==1)
								{
									$cell_value="";
									$cell_value=$cell->getCalculatedValue();
									strlen($cell_value);
									//echo"in if 1<br>";
									//echo "cell_value2=".$cell_value[2]."<br>cell_value5=".$cell_value[5];
									if($cell_value[2]!="." || $cell_value[5]!=".")
									{
										//echo "in delete 1<br>";
										delete_file_with_error_message("dd.mm.yy",$original_file_name,$tmp_upload_file,$tmp_val);										
										$flag=1;															
										break;
									}
								}											
							}
						}
					}									
				}					
			}
			if($flag==1)
			{								
				break;
			}
		}
	}
							
	function validate_csv_fields($format_ids_cnd,$upload_type,$final_file_name,$sub_files_ids,$tmp_upload_file)
	{
		
		global $flag;
		copy($_FILES['file_'.$format_ids_cnd."_".$sub_files_ids]['tmp_name'],$tmp_upload_file);
		$original_file_name=basename($_FILES['file_'.$format_ids_cnd."_".$sub_files_ids]['name']);
		$file = fopen($tmp_upload_file,"r");
		if($file)
		{						
			while(($data = fgetcsv($file, 1000, ",")) !== FALSE) 
			{									
				if($cnt>0)
				{                                                              
				  // Billing date Route Sold-to party VehicleNo Google Location IN TIME OUT TIME DURATION(H:m:s)
					$num = count($data);                            
					$tmp_str="";
					for ($c=0; $c < $num; $c++) 
					{
						//echo "format_id_cnd=".$format_ids_cnd."<br>"; 
						if((($upload_type=="master") && ($format_ids_cnd=="#4" || $format_ids_cnd=="#5")) || ($upload_type=="get_report" && $format_ids_cnd=="#1"))
						{												
							if($c==0 || $c==2)
							{													
								if((!preg_match('/^[0-9]{2}-[0-9]{2}-[0-9]{2}$/', $data[$c])) && (!preg_match('/^[0-9]{2}\/[0-9]{2}\/[0-9]{2}$/', $data[$c])))
								{
									$tmp_val=$cnt." => ".$c." => ".$data[$c];
									//echo "tmp_val 3=".$tmp_val."<br>";
									delete_file_with_error_message("",$original_file_name,$tmp_upload_file,$tmp_val);										
									$flag=1;														
									break;
								}																										
							}
							if($c==1 || $c==3)
							{
								if(!preg_match("/^(2[0-3]|[01]?[1-9]):([0-5]?[0-9]):([0-5]?[0-9])$/", $data[$c]))												
								{
									$tmp_val=$cnt." => ".$c." => ".$data[$c];															
									delete_file_with_error_message("h:mm:ss",$original_file_name,$tmp_upload_file,$tmp_val);	
									$flag=1;														
									break;													
								}
							}
							if($c==7)
							{														
								if (preg_match('/[\'^£$%&}{@#~?><>,|=+¬]/', $data[$c]))
								{
									$tmp_val=$cnt." => ".$c." => ".$data[$c];
									delete_file_with_error_message("h:mm:ss",$original_file_name,$tmp_upload_file,$tmp_val);	
									$flag=1;														
									break;						
								}									
							}
						}
						else if(($upload_type=="master") && ($format_ids_cnd=="#6"))
						{
							if($c==0)
							{
								//echo "in if";
								if (preg_match('/[\'^£$%&}{@#~?><>,|=+¬]/', $data[$c]))
								{
									$tmp_val=$cnt." => ".$c." => ".$data[$c];
									delete_file_with_error_message("h:mm:ss",$original_file_name,$tmp_upload_file,$tmp_val);	
									$flag=1;														
									break;						
								}									
							}
						}
						else if($upload_type=="master")
						{
							if($c==2 || $c==3)
							{														
								if(!preg_match("/^(2[0-3]|[01]?[0-9]):([0-5]?[0-9])$/", $data[$c]))
								{
									$tmp_val=$cnt." => ".$c." => ".$data[$c];
									delete_file_with_error_message("h:mm",$original_file_name,$tmp_upload_file,$tmp_val);								
									$flag=1;														
									break;
								}									
							}
						}
						else if($upload_type=="get_report" && $format_ids_cnd=="#2")
						{
							if($sub_files_ids=="#1")
							{							
								if($c==0)
								{
									if((!preg_match('/^[0-9]{2}.[0-9]{2}.[0-9]{2}$/', $data[$c])))
									{
										$tmp_val=$cnt." => ".$c." => ".$data[$c];
										delete_file_with_error_message("dd.mm.yy",$original_file_name,$tmp_upload_file,$tmp_val);										
										$flag=1;															
										break;
									}
								}
								if($c==4 || $c==5)
								{
									$cell_value=$cell->getCalculatedValue();
									if(!preg_match("/^(2[0-3]|[01]?[1-9]):([0-5]?[0-9]):([0-5]?[0-9])$/", $data[$c]))
									{
										$tmp_val=$cnt." => ".$c." => ".$data[$c];
										delete_file_with_error_message("h:mm:ss",$original_file_name,$tmp_upload_file,$tmp_val);	
										$flag=1;															
										break;																			
									}
								}
							}
							if($sub_files_ids=="#2")
							{
								if($c==0)
								{
									$tmp_val=$cnt." => ".$c." => ".$data[$c];
									if((!preg_match('/^[0-9]{2}.[0-9]{2}.[0-9]{2}$/', $data[$c])))
									{													
										delete_file_with_error_message("dd.mm.yy",$original_file_name,$tmp_upload_file,$tmp_val);										
										$flag=1;															
										break;
									}
								}											
							}
						}
					}										
				}
				if($flag==1)
				{								
					break;
				}
				$cnt++;											
			}
		}	
	}
							
?>
	</body>
</html>



