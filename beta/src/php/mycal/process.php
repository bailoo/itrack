<?php
include('config.php');

$type = $_POST['type'];

if($type == 'new')
{
	$startdate = $_POST['startdate'].'+'.$_POST['zone'];
	$title = $_POST['title'];
	$insert = mysqli_query($con,"INSERT INTO calendar(`title`, `startdate`, `enddate`, `allDay`) VALUES('$title','$startdate','$startdate','true')");
	$lastid = mysqli_insert_id($con);
	echo json_encode(array('status'=>'success','eventid'=>$lastid));
}

if($type == 'new_repeat')
{
	$startdate = $_POST['startdate'].'+'.$_POST['zone'];
	$title = $_POST['title'];
	$dow = $_POST['dow'];
	$repeat_month = $_POST['repeat_month'];
	
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
		$endOfCycle=$endOfCycle.' 00:00:00+'.$_POST['zone'];
	}
	if($repeat_month=="1")
	{ 
		
		$timeend = strtotime($_POST['startdate']);
		$endOfCycle = date("Y-m-d", strtotime("last day of next month", $timeend));//+1 month ,last day of next month,12 day
		$endOfCycle=$endOfCycle.' 00:00:00+'.$_POST['zone'];
	}
	if($repeat_month=="2")
	{ 
		$timeend = strtotime($_POST['startdate']);
		$endOfCycle = date("Y-m-d", strtotime("+2 month", $timeend));//+1 month ,last day of next month,12 day
		$endOfCycle=$endOfCycle.' 00:00:00+'.$_POST['zone'];
		
	}
	if($repeat_month=="3")
	{ 				
		$timeend = strtotime($_POST['startdate']);
		$endOfCycle = date("Y-m-d", strtotime("+3 month", $timeend));//+1 month ,last day of next month,12 day
		$endOfCycle=$endOfCycle.' 00:00:00+'.$_POST['zone'];
		
	}
	
	$insert = mysqli_query($con,"INSERT INTO calendar(`title`, `startdate`, `enddate`, `allDay`,`dow`) VALUES('$title','$startdate','$endOfCycle','false','$dow')");
	$lastid = mysqli_insert_id($con);
	echo json_encode(array('status'=>'success','eventid'=>$lastid));
}

if($type == 'changetitle')
{
	$eventid = $_POST['eventid'];
	$title = $_POST['title'];
	$person = $_POST['person'];
	$update = mysqli_query($con,"UPDATE calendar SET title='$title' , person='$person' where id='$eventid'");
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
	$update = mysqli_query($con,"UPDATE calendar SET  person='$person' where id='$eventid'");
	if($update)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}

if($type == 'resetdate')
{
	$title = $_POST['title'];
	$startdate = $_POST['start'];
	$enddate = $_POST['end'];
	$eventid = $_POST['eventid'];
	$update = mysqli_query($con,"UPDATE calendar SET title='$title', startdate = '$startdate', enddate = '$enddate' where id='$eventid'");
	if($update)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}

if($type == 'remove')
{
	$eventid = $_POST['eventid'];
	$delete = mysqli_query($con,"DELETE FROM calendar where id='$eventid'");
	if($delete)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}

if($type == 'fetch')
{
	$bin_person=array();
	$account_person=array();
	$query_person = mysqli_query($con, "SELECT * FROM person where status=1");
	while($fetch_person = mysqli_fetch_array($query_person,MYSQLI_ASSOC))
	{
		if($fetch_person['gender']==1)
		{
			$gender="Male";
		}
		else
		{
			$gender="Female";
		}
		$bin_person[$fetch_person['person_id']]=$fetch_person['person_name']."~".$gender;	
		$account_person[]=$fetch_person['person_id'];
					
	}
	
	//print_r($bin_person);
	
	$events = array();
	$query = mysqli_query($con, "SELECT * FROM calendar");
	while($fetch = mysqli_fetch_array($query,MYSQLI_ASSOC))
	{
		$e = array();
		$e['id'] = $fetch['id'];
		$e['title'] = $fetch['title'];
		$e['start'] = $fetch['startdate'];
		$e['end'] = $fetch['enddate'];
		
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
				$e['color']  = 'orange';
				
		}
		else{
			$e['ranges'] ="";
			//$e['color']  = 'blue';
			$e['dowend'] = "";
		}
		
		

		array_push($events, $e);
	}
	echo json_encode($events);
}


?>