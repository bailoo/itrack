<?php
ini_set('max_execution_time', 1200); //300 seconds = 5 minutes
include('util_php_mysqli_connectivity.php');
$common_account_id=$_REQUEST['cid'];
date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
$date=date("Y-m-d H:i:s");
$type = $_POST['type'];

if($type == 'new')
{
	//$startdate = $_POST['startdate'].'+'.$_POST['zone'];
        $startdate = $_POST['startdate'];
	$title = $_POST['title'];
        $create_id=$_REQUEST['account_id'];
        
        //echo"INSERT INTO calendar_visit(`title`, `startdate`, `enddate`, `allDay`,`status`,`create_date`,`create_id`,`account_id`) VALUES('$title','$startdate','$startdate','true','1','$date','$create_id','$common_account_id')";
       // exit();
	$insert = mysqli_query($con,"INSERT INTO calendar_visit(`title`, `startdate`, `enddate`, `allDay`,`status`,`create_date`,`create_id`,`account_id`) VALUES('$title','$startdate','$startdate','true','1','$date',$create_id,$common_account_id)");
	$lastid = mysqli_insert_id($con);
	echo json_encode(array('status'=>'success','eventid'=>$lastid));
}

if($type == 'new_repeat')
{
	//$startdate = $_POST['startdate'].'+'.$_POST['zone'];
        $startdate = $_POST['startdate'];
	$title = $_POST['title'];
	$dow = $_POST['dow'];
	$repeat_month = $_POST['repeat_month'];
	$create_id=$_REQUEST['account_id'];
        $common_account_id=$_REQUEST['cid'];
	if($dow!="")
	{
		$dow="[ ".$dow." ]";
		
	}
	if($repeat_month=="0")
	{ 
		
		$timestamp = strtotime($_POST['startdate']);
		$daysRemaining = (int)date('t', $timestamp) - (int)date('j', $timestamp);
		
		$timeend = strtotime($_POST['startdate']);
		$endOfCycle = date("Y-m-d", strtotime($daysRemaining." day", $timeend));//+1 month ,last day of next month,12 day
		//$endOfCycle=$endOfCycle.' 00:00:00+'.$_POST['zone'];
                $endOfCycle=$endOfCycle.' 00:00:00+';
	}
	if($repeat_month=="1")
	{ 
		
		$timeend = strtotime($_POST['startdate']);
		$endOfCycle = date("Y-m-d", strtotime("last day of next month", $timeend));//+1 month ,last day of next month,12 day
		//$endOfCycle=$endOfCycle.' 00:00:00+'.$_POST['zone'];
                $endOfCycle=$endOfCycle.' 00:00:00+';
	}
	if($repeat_month=="2")
	{ 
		$timeend = strtotime($_POST['startdate']);
		$endOfCycle = date("Y-m-d", strtotime("+2 month", $timeend));//+1 month ,last day of next month,12 day
		//$endOfCycle=$endOfCycle.' 00:00:00+'.$_POST['zone'];
                $endOfCycle=$endOfCycle.' 00:00:00+';
		
	}
	if($repeat_month=="3")
	{ 				
		$timeend = strtotime($_POST['startdate']);
		$endOfCycle = date("Y-m-d", strtotime("+3 month", $timeend));//+1 month ,last day of next month,12 day
		//$endOfCycle=$endOfCycle.' 00:00:00+'.$_POST['zone'];
                $endOfCycle=$endOfCycle.' 00:00:00+';
		
	}
	//echo "INSERT INTO calendar_visit(`title`, `startdate`, `enddate`, `allDay`,`dow`,`status`,`create_date`,`create_id`,`account_id`) VALUES('$title','$startdate','$endOfCycle','false','$dow','1','$date','$create_id','$common_account_id')";exit();
	$insert = mysqli_query($con,"INSERT INTO calendar_visit(`title`, `startdate`, `enddate`, `allDay`,`dow`,`status`,`create_date`,`create_id`,`account_id`) VALUES('$title','$startdate','$endOfCycle','false','$dow','1','$date','$create_id','$common_account_id')");
	$lastid = mysqli_insert_id($con);
	echo json_encode(array('status'=>'success','eventid'=>$lastid));
}

if($type == 'changetitle')
{
	$eventid = $_POST['eventid'];
	$title = $_POST['title'];
	$person = $_POST['person'];
	$update = mysqli_query($con,"UPDATE calendar_visit SET title='$title' , person='$person' where id='$eventid'");
	if($update)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}

if($type == 'assigned_deassigned_person')
{
	$eventid = $_POST['eventid'];
	$assign_person = $_POST['assign_person'];
	$deassign_person_remain = $_POST['deassign_person_remain'];
        $description = $_POST['description'];
        $edit_id = $_POST['account_id'];
        
	if($assign_person!="" && $deassign_person_remain!="")
	{
		$person=$assign_person.",".$deassign_person_remain;
	}
	else if ($assign_person!="" && $deassign_person_remain=="")
	{
		$person=$assign_person;
	}
	else if ($assign_person=="" && $deassign_person_remain!="")
	{
		$person=$deassign_person_remain;
	}
	else
	{
		$person="";
	}
	$update = mysqli_query($con,"UPDATE calendar_visit SET  person='$person',description='$description',edit_id='$edit_id',edit_date='$date' where id='$eventid'");
	
        if($update)
        {
           // echo"in UPDATE calendar_visit SET  person='$person',description='$description',edit_id='$edit_id',edit_date='$date' where id='$eventid'";exit();
		echo json_encode(array('status'=>'success'));
        }
	else
        {
           // echo"out";exit();
		echo json_encode(array('status'=>'failed'));
        }
}

if($type == 'resetdate')
{
	$title = $_POST['title'];
	$startdate = $_POST['start'];
	$enddate = $_POST['end'];
	$eventid = $_POST['eventid'];
        $edit_id = $_POST['account_id'];
	$update = mysqli_query($con,"UPDATE calendar_visit SET title='$title', startdate = '$startdate', enddate = '$enddate',edit_date='$date',edit_id='$edit_id' where id='$eventid'");
	if($update)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}

if($type == 'remove')
{
	$eventid = $_POST['eventid'];
        $edit_id = $_POST['account_id'];
	$delete = mysqli_query($con,"UPDATE calendar_visit SET status=0,edit_date='$date',edit_id='$edit_id' where id='$eventid'");
	if($delete)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}

if($type == 'fetch')
{
	$bin_person=array();
	$account_person=array();
	$query_person = mysqli_query($con, "SELECT * FROM person where status=1 and user_account_id='$common_account_id'");
	while($fetch_person = mysqli_fetch_array($query_person,MYSQLI_ASSOC))
	{
		/*if($fetch_person['gender']==1)
		{
			$gender="Male";
		}
		else
		{
			$gender="Female";
		}*/
                $gender="Male";
		$bin_person[$fetch_person['person_id']]=$fetch_person['person_name']."~".$gender;	
		$account_person[]=$fetch_person['person_id'];
					
	}
	
	//print_r($bin_person);
	
	$events = array();
	$query = mysqli_query($con, "SELECT * FROM calendar_visit where account_id='$common_account_id' and status=1");
	while($fetch = mysqli_fetch_array($query,MYSQLI_ASSOC))
	{
		$e = array();
		$e['id'] = $fetch['id'];
		$e['title'] = $fetch['title'];
		$e['start'] = $fetch['startdate'];
		$e['end'] = $fetch['enddate'];
                
		$description="<input type=textbox id='descriptionn_".$fetch['id']."' value= '".$fetch['description']."'> ";
                $e['description'] = $description;
		$person_checkbox="";
		$person_ids=array();
		$assigned_person=array();
		if($fetch['person']!="")
		{
			$person_ids=explode(',',$fetch['person']);
			$e['person'] = count($person_ids);			
			foreach($person_ids as $person_id)
			{
				$person_checkbox.="<input type=checkbox name='deassign_person_".$fetch['id']."' value='".trim($person_id)."' checked>".$bin_person[trim($person_id)]."&nbsp";
				$assigned_person[]=trim($person_id);
			}
		}
		else
		{
			$e['person'] = 0;
		}
		$e['person_name_checkbox'] = $person_checkbox;
		
		$person_uncheckbox="";
		$unmatched_persons=array_diff($account_person,$assigned_person);
		foreach($unmatched_persons as $unmatched_person)
		{
			$person_uncheckbox.="<input type=checkbox name='assign_person_".$fetch['id']."' value='".trim($unmatched_person)."' >".$bin_person[trim($unmatched_person)]."&nbsp";
		}		
		$e['person_name_uncheckbox'] = $person_uncheckbox;
		
		$allday = ($fetch['allDay'] == "true") ? true : false;
		$e['allDay'] = $allday;
		
		
		$e['dow'] = $fetch['dow'];
		//$e['start'] ='2016-10-01';
		//$e['end'] ='2016-11-01';
		if($fetch['dow']!="")
		{
		
				//$e['ranges'] = '[{"2016/10/01","2016/11/01"}]';
				//$e['dowstart'] = "2016-10-01 00:00:00";
				$e['dowend'] = $fetch['enddate'];//"2016-12-01 00:00:00";
				//$e['durationDays']=66;
				$e['color']  = 'brown';
				
		}
		else{
			$e['ranges'] ="";
			$e['color']  = '#1e7a36';
			$e['dowend'] = "";
		}
		
		

		array_push($events, $e);
	}
        
	echo json_encode($events);
        //echo "jjj";
}


?>

