<?php
  for($i=0;$i<$size_utype_session;$i++)
  {
    //echo"<br>user_type_id1=".$user_type_name_session[$i];
    if($user_type_name_session[$i] == "fleet")
    {
       $fleet_user_type=1;
    }
    if($user_type_name_session[$i] == "courier")
    {
       $courier_user_type=1;
    }
    if($user_type_name_session[$i] == "school")
    {
       $school_user_type=1;
    }
    if($user_type_name_session[$i] == "pos")
    {
       $pos_user_type=1;
    }
    if($user_type_name_session[$i] == "mining")
    {
       $mining_user_type=1;
    }
	if($user_type_name_session[$i] == "person")
    {
       $person_user_type=1;	   
    }
  }
  
 if(@$person_user_type!=1 || ((@$fleet_user_type==1 || @$mining_user_type==1 || @$courier_user_type==1 || @$school_user_type ==1 || @$pos_user_type==1)))
  {
	$report_type="Vehicle";
  }
  else
  {
	$report_type="Person";
  }
?>
