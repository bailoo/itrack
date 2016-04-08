<?php
//include_once('util_session_variable.php');  	   //util_session_variable.php sets values in session
//include_once('util_php_mysql_routem_connectivity.php');   //util_php_mysql_connectivity.php make set connection of user to database 
include_once('util_php_mysql_connectivity.php');
include_once('filter_route_mob_app.php'); 
$common_date=date("Y-m-d H:i:s");	
$reader_group_id = trim($_POST['readerGroupId']);
$login_id_1 =trim($_POST['user_id']);
$password_1=md5(trim($_POST['password']));
$imei_1=trim($_POST['imei']);
$sync = $_POST['sync'];

$data_process=0;
/*$reader_group_id="0001";
$login_id_1='demo_android';
$imei_1='356262054690264';
$password_1=md5('demo_android');
$sync="routeData";*/
//echo "SYNC=".$sync;
if($sync=="check_connection")
{
	echo "Success\n";
}
/*else if($reader_group_id=="")
{
	echo "Group Id is NULL. Please Enter Valid Group ID";
}*/
else if($login_id_1=="")
{
	echo "Login Id is NULL. Please Enter Valid Login ID";
}
else if($password_1=="")
{
	echo "Password is NULL. Please Enter Valid Password";
}
else
{
	$data_process=1;
}	

if($data_process==1)
{
	if($sync=="login")
	{
		/*$query ="SELECT account_id from account where user_id='$login_id_1' AND password='$password_1' AND group_id".
				"='$reader_group_id' AND imei_no='$imei_1' AND status=1";*/
		$query ="SELECT account_id from account where user_id='$login_id_1' AND password='$password_1' AND status=1";				
		//echo "query=".$query."<br>";
		$result=mysql_query($query,$DbConnection);
		$num_rows=mysql_num_rows($result);
		//echo "\nDBCon=".$DbConnection." ,num_rows=".$num_rows;
		if($num_rows>0)
		{
			echo "success";						
		}
		else
		{
			echo "failure";
		}		
	}
	else if($sync=="insertFile")
	{
		$current_dt = date("Y_m_d_H_i_s");
		$route_name=$_FILES['upload_file']['name'];
		$fname = $imei_1."@".$current_dt."@".$_FILES['upload_file']['name'];
		$filetmp="/var/www/html/vts/beta/src/php/demo_group/raw_data/".$imei_1."@".$current_dt."@".$_FILES['upload_file']['name'];
		$filetmp1="demo_group/filtered_data/".$imei_1."@".$current_dt."@".$_FILES['upload_file']['name'];
		$route_name=$_POST["route_name"];
		$sQuery="SELECT route_name FROM android_route_info WHERE route_name='$route_name' AND imei_no='$imei_1' ".
				"AND status=1";
		//echo "sQuery=".$sQuery."<br>";
		$sResult=mysql_query($sQuery,$DbConnection);
		$sNumRow=mysql_num_rows($sResult);
		if($sNumRow>0)
		{
			echo "Already Exist";
		}
		else
		{
			if(move_uploaded_file($_FILES['upload_file']['tmp_name'],$filetmp))
			{
				//####### FILTER ROUTE
				get_route_detail($fname);
				
				$iQuery="INSERT INTO android_route_info(route_name,route_file_path,route_file_path1,imei_no,status,create_id,create_date) VALUES(".
						"'$route_name','$filetmp','$filetmp1','$imei_1',1,1,'$common_date')";
				$iResult=mysql_query($iQuery,$DbConnection);			
				/*$data[]=array("routeName"=>$route_name,"routeName"=>$route_name,"filePath"=>$filetmp);
				echo json_encode($data);*/
				echo "success,".trim($route_name).",".trim($fname);
			}
			else
			{
				echo"File Not Uploaded";
			}
		}		
	}
	else if($sync=="routeData")
	{
		
		$sQuery="SELECT route_name,route_file_path1 FROM android_route_info WHERE imei_no='$imei_1' AND status=1";
		//echo "sQuery=".$sQuery."<br>";
		$sResult=mysql_query($sQuery,$DbConnection);
		$finalString="";
		$data=array();
		while($row=mysql_fetch_object($sResult))
		{	
			$android_file_name=explode("/",$row->route_file_path1);
			$data[]=array("routeName"=>$row->route_name,"routFilePath"=>$android_file_name[2]);			
			//$finalString=$finalString.$row->route_name.",".$row->route_file_path.":";
		}	
		echo json_encode($data);
	}
}
//echo "user_name=".$user_name."password=".$password;
?>

