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
//error_reporting(-1);
//ini_set('display_errors', 'On');
ini_set('auto_detect_line_endings',TRUE);
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
//print_r($pathInPieces);
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3]; ////// local path
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]; /// server Path
$filePathToS3Wrapper=$pathToRoot."/s3/S3Wrapper.php";
include_once($filePathToS3Wrapper);
if($account_id=="322" || $account_id=="1100" || $account_id=="1115" || $account_id=="1568" || $account_id=="1882")
{
    //echo "in if";
    include_once('station_sort_mumbai.php');
}
else if($account_id=="231" || $account_id=="232" || $account_id=="2068")
{	
    //include_once('station_sort.php');
    include_once('station_sort_delhi.php');
}
else if($account_id=="568")
{	
    //include_once('station_sort.php');
    //echo "<br>Tanker";
    include_once('station_sort_tanker.php');
}
else if($account_id=="718")
{
    include_once("station_sort_pdu.php");
}
else
{
    include_once('station_sort.php');
}

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
//date_default_timezone_set('Europe/London');
require_once 'PHPExcel/IOFactory.php';

set_time_limit(200);
$date1=explode(" ",$date);
if($account_id!="")
{
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
                    $file_name_2 = explode(".",$file_name_1[2]); // returns "d"
                    $post_formatid=$file_name_2[0];
                    $post_formatid_arr[]="#".$post_formatid;
                    $post_filename_arr[]=$file_name;
                }
            }
            $delete_post_formatid_arr[]=$post_formatid_arr; // for delete the master file except format id #3
            $delete_post_filename_arr[]=$post_filename_arr; // for delete the master file except format id #3
            $file_ids_arr=explode(",",$format_ids_1[1]);	
        }
        else if($upload_type=="get_report")
        {
            $random_number=rand();
            $format_ids_1=explode(":",$format_ids);
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
                    //unlink($tmp_upload_file);
                    //delete_files($post_formatid_arr,$post_filename_arr,$format_ids_1[0]);
                    //////// for delete all files simultaniously ////////
                    //$delete_post_formatid_arr[]=$post_formatid_arr;
                    //$delete_post_filename_arr[]=$post_filename_arr;
                    $delete_format_ids_1[]=$format_ids_1[0];				
					
                    //////// for upload all files simultaniously ////////
                    $upload_final_dest_file[]=$final_dest_file;
                    $upload_format_ids_1[]= $format_ids_1[0];
                    $upload_upload_type[]=$upload_type;
                    $upload_file_ids_arr[]=$file_ids_arr[$i];
                    //upload_files($final_dest_file,$format_ids_1[0],$upload_type,$file_ids_arr[$i]);				
                }
            }
            else if($upload_type=="get_report")
            {			
                $final_dest_file="gps_report/".$account_id."/upload/".$date1[0]."/".$final_file_name;	
                $file_extension=explode('.',$final_file_name);
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
                //validate_excel_fields($format_ids_1[0],$upload_type,$final_file_name,$file_ids_arr[$i],$tmp_upload_file);
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
	function upload_files($final_dest_file,$format_ids_cnd,$upload_type,$sub_files_ids)
	{
		$account_id=$_SESSION['account_id'];
		//echo "final_dest_file=".$final_dest_file." format_ids_cnd=".$format_ids_cnd."sub_files_ids=".$sub_files_ids." upload_type=".$upload_type."account_id=".$_SESSION['account_id']."<br>";
		if(isset($final_dest_file))
		{
                    $overwrite=true;
                    //echo "uploadPath=".$final_dest_file." tmpPath=".$_FILES['file_'.$format_ids_cnd."_".$sub_files_ids]['tmp_name']."<br>";
                    $fileUploadStatus=uploadFile($final_dest_file, $_FILES['file_'.$format_ids_cnd."_".$sub_files_ids]['tmp_name'], $overwrite);
		    //echo "fileUploadStatus=".$fileUploadStatus."<br>";
                    //echo 'File1='.'file_'.$format_ids_cnd."_".$sub_files_ids.' ,File2='.$_FILES['file_'.$format_ids_cnd."_".$sub_files_ids]['tmp_name']." ,final_dest=".$final_dest_file."<br>";	
		    copy($_FILES['file_'.$format_ids_cnd."_".$sub_files_ids]['tmp_name'],$final_dest_file);
                    //if(copy($_FILES['file_'.$format_ids_cnd."_".$sub_files_ids]['tmp_name'],$final_dest_file))
			if($fileUploadStatus!='')
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
				chmod($final_dest_file, 0777);
				if((($upload_type=="master" && $format_ids_cnd=="#4") || ($upload_type=="master" && $format_ids_cnd=="#9")) && ($account_id=='231' || $account_id=='322' || $account_id=='723' || $account_id=='568' || $account_id=='2' || $account_id=='1100'  || $account_id=='1115'  || $account_id=='718' || $account_id=="232") || $account_id=="1568" || $account_id=="1882" || $account_id=="2068")
				{
                                    //echo "in if <br>";
					if($account_id=='568')
					{
						$shift_time = "ZBVE";
					}
					else
					{
						$shift_time = "ZPME";
					}
					get_master_detail($account_id, $shift_time); 
				}
				if((($upload_type=="master" && $format_ids_cnd=="#5") || ($upload_type=="master" && $format_ids_cnd=="#10")) && ($account_id=='231' || $account_id=='322' || $account_id=='723' || $account_id=='568' || $account_id=='2' || $account_id=='1100' || $account_id=='1115'  || $account_id=='718' || $account_id=="232" || $account_id=="1568" || $account_id=="1882" || $account_id=="2068"))
				{
					//echo "in if 1 <br>";
                                        if($account_id=='568')
                                        {
                                                $shift_time = "ZBVM";
                                        }
                                        else
                                        {
						$shift_time = "ZPMM";
					}
					get_master_detail($account_id, $shift_time);
				}
                                if( ($upload_type=="master" && $format_ids_cnd=="#15") && ($account_id=='1882'))
				{
					//echo "in if 1 <br>";
                                        $shift_time = "ZPTST";
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
                                    delFile("gps_report/".$account_id."/master/".$post_filename_arr[$fd]);
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
								/*if (preg_match('/[\'^�$%&*()}{@#~?><>,|=_+�-]/', $vehicle_name_local))*/
								if (preg_match('/[\'^�$%&}{@#~?><>,|=+�]/', $vehicle_name_local))
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
								/*if (preg_match('/[\'^�$%&*()}{@#~?><>,|=_+�-]/', $vehicle_name_local))*/
								if (preg_match('/[\'^�$%&}{@#~?><>,|=+�]/', $vehicle_name_local))
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
                unlink($tmp_upload_file);
                
	}
							
	function validate_csv_fields($format_ids_cnd,$upload_type,$final_file_name,$sub_files_ids,$tmp_upload_file)
	{
		global $account_id;
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
						//echo "format_id_cnd=".$format_ids_cnd."upload_type=".$upload_type."<br>"; 
						if((($upload_type=="master") && ($format_ids_cnd=="#4" || $format_ids_cnd=="#5")) || ($upload_type=="get_report" && $format_ids_cnd=="#1"))
						{
							//echo "datgc=".$data[$c]."<br>";
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
								if(!preg_match("/^(0?\d|1\d|2[0-3]):[0-5]\d:[0-5]\d$/", $data[$c]))												
								{
									$tmp_val=$cnt." => ".$c." => ".$data[$c];															
									delete_file_with_error_message("h:mm:ss",$original_file_name,$tmp_upload_file,$tmp_val);	
									$flag=1;														
									break;													
								}
							}
							if($c==7)
							{														
								if (preg_match('/[\'^�$%&}{@#~?><>,|=+�]/', $data[$c]))
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
								if (preg_match('/[\'^�$%&}{@#~?><>,|=+�]/', $data[$c]))
								{
									$tmp_val=$cnt." => ".$c." => ".$data[$c];
									delete_file_with_error_message("h:mm:ss",$original_file_name,$tmp_upload_file,$tmp_val);	
									$flag=1;														
									break;						
								}									
							}
						}
						else if(($upload_type=="master" && $format_ids_cnd=="#9") || ($upload_type=="master" && $format_ids_cnd=="#10"))
						{
							//echo "in if3";
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
									if(!preg_match("/^(0?\d|1\d|2[0-3]):[0-5]\d:[0-5]\d$/", $data[$c]))
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
							//echo "datac=".$data[$c]."<br>";
						}
                                                else if($upload_type=="master" && $account_id==231 && $format_ids_cnd=="#1")
						{
							if($c==3 || $c==4)
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
						else if($upload_type=="master" && $sub_files_ids!="#15")
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
									//$cell_value=$cell->getCalculatedValue();
									if(!preg_match("/^(0?\d|1\d|2[0-3]):[0-5]\d:[0-5]\d$/", $data[$c]))
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
                unlink($tmp_upload_file);
	}
							
?>
	</body>
</html>



